<?php

namespace Yahay\PhpDependencyInjector;

/**
 * Dependency map
 */
class DependencyMap
{
    /**
     * @var array{string:BuilderDataDto} Singleton dependencies
     */
    protected(set) array $singletons = [];

    /**
     * @var array{string:BuilderDataDto} Access generated dependencies
     */
    protected(set) array $classics = [];

    /**
     * Register a singleton dependency builder
     * @param string $class Class name
     * @param BuilderDataDto $builderData Builder data
     * @return $this
     */
    public function registerSingleton(string $class,BuilderDataDto $builderData): self
    {
        $this->singletons[$class] = $builderData;

        return $this;
    }

    /**
     * Register a dependency builder
     * @param string $class Class name
     * @param BuilderDataDto $builderData Builder data
     * @attention The build closure will be called each time this dependency is requested
     * @return $this
     */
    public function register(string $class,BuilderDataDto $builderData): self
    {
        $this->classics[$class] = $builderData;

        return $this;
    }
}