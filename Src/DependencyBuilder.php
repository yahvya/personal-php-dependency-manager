<?php

namespace Yahay\PhpDependencyInjector;

use Exception;
use ReflectionClass;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionUnionType;
use Throwable;

/**
 * Dependency builder utility
 * @attention Be aware of circular dependencies
 * @attention You should use types while coding
 */
class DependencyBuilder
{
    /**
     * @param DependencyManager $dependencyManager Associated dependency manager
     */
    public function __construct(
        protected(set) DependencyManager $dependencyManager,
    )
    {
    }

    /**
     * Build the dependency
     * @param BuilderDataDto $builderDataDto Builder data
     * @return mixed Builder method call result
     * @throws DependencyManagerException On error
     * @attention Be aware of circular dependencies
     */
    public function build(BuilderDataDto $builderDataDto): mixed
    {
        if($builderDataDto->buildResult !== null)
        {
            return $builderDataDto->buildResult;
        }

        $builderParameters = $builderDataDto->manualParameters;

        try
        {
            $builderReflection = new ReflectionFunction(function: $builderDataDto->builder);

            foreach ($builderReflection->getParameters() as $builderParameter)
            {
                $parameterName = $builderParameter->getName();

                if (array_key_exists(key: $parameterName, array: $builderDataDto->manualParameters))
                {
                    continue;
                }

                $builderParameters[$parameterName] = $this->getInstanceOf(type: $builderParameter->getType());
            }

            return $builderDataDto->builder->__invoke(...$builderParameters);
        }
        catch (Throwable $e)
        {
            throw new DependencyManagerException(
                errorType: DependencyManagerError::DEPENDENCY_BUILD_FAIL,
                e: $e
            );
        }
    }

    /**
     * Create an instance of the given type
     * @param ReflectionIntersectionType|ReflectionNamedType|ReflectionUnionType|null $type
     * @return object|null Created instance or null if the type is a built-in type and the nullability is not allowed
     * @throws Throwable On error
     * @attention The resolution of intersection types could be surprising. It checks in all the registered classes in the dependency manager if there is one which satisfies the intersection type and picks the first one.
     * @attention If it's a built-in type, it should allow null or the builder will throw an exception
     * @attention If it's a union type, it will try to create an instance of the first type which satisfies the union type
     */
    public function getInstanceOf(ReflectionIntersectionType|ReflectionNamedType|ReflectionUnionType|null $type): ?object
    {
        if ($type === null)
        {
            return null;
        }
        else if ($type instanceof ReflectionNamedType)
        {
            return $this->getNamedTypeInstance(type: $type);
        }
        else if($type instanceof ReflectionUnionType)
        {
            return $this->getNamedUnionTypeInstance(type: $type);
        }
        else
        {
            return $this->getIntersectionTypeInstance(type: $type);
        }
    }

    /**
     * Get the instance of a named type
     * @param ReflectionNamedType $type Named type
     * @return object|null Created instance
     * @throws Throwable On error
     * @attention If it's a built-in type, it should allow null or the builder will throw an exception
     */
    protected function getNamedTypeInstance(ReflectionNamedType $type): ?object
    {
        if($type->isBuiltin())
        {
            if($type->allowsNull())
            {
                return null;
            }

            throw new Exception(message: "Cannot create an instance of a built-in type");
        }

        $reflectionClass = new ReflectionClass(objectOrClass: $type->getName());
        $parameters = [];

        foreach($reflectionClass->getConstructor()?->getParameters() ?? [] as $parameter)
        {
            $parameters[] = $this->getInstanceOf(type: $parameter->getType());
        }

        return $reflectionClass->newInstanceArgs(args: $parameters);
    }

    /**
     * Create an instance of the given union type
     * @param ReflectionUnionType $type Union type
     * @return object|null Created instance
     * @throws Exception On error
     * @attention If it's a union type, it will try to create an instance of the first type which satisfies the union type
     */
    protected function getNamedUnionTypeInstance(ReflectionUnionType $type): ?object
    {
        foreach($type->getTypes() as $type)
        {
            try{
                return $this->getInstanceOf(type: $type);
            }
            catch(Throwable)
            {}
        }

        throw new Exception(message: "Nothing satisfies the union type");
    }

    /**
     * Create an instance of the given intersection type
     * @param ReflectionIntersectionType $type Intersection type
     * @return object Created instance
     * @throws Throwable On error
     */
    protected function getIntersectionTypeInstance(ReflectionIntersectionType $type): object
    {
        $classes = array_merge(
            array_keys(array: $this->dependencyManager->dependenciesMap->singletons),
            array_keys(array: $this->dependencyManager->dependenciesMap->classics)
        );

        $typesClasses = array_map(
            callback: fn(ReflectionNamedType $type):string => $type->getName(),
            array: $type->getTypes()
        );

        // search the first class which satisfies the intersection type classes
        foreach($classes as $class)
        {
            $matchAllTypes = true;

            foreach($typesClasses as $typeClass)
            {
                if(!is_subclass_of(object_or_class: $class, class: $typeClass))
                {
                    $matchAllTypes = false;
                    break;
                }
            }

            if($matchAllTypes)
            {
                return $this->dependencyManager->getDependency(class: $class);
            }
        }

        throw new Exception(message: "Nothing satisfies the intersection type");
    }
}