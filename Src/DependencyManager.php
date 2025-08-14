<?php

namespace Yahay\PhpDependencyInjector;

use Closure;

/**
 * Dependencies manager
 * @notice Pseudo singleton class. You can use it like that or you can use DependencyManager::get() which will return the same instance. The constructor is opened
 */
class DependencyManager
{
    /**
     * @var DependencyManager|null Manager instance
     */
    protected static ?DependencyManager $instance = null;

    /**
     * @var array{string:} Dependencies map
     */
    protected(set) array $dependenciesMap = [];

    /**
     * Provide a registered dependency
     * @param string $class Class name
     * @return object The build instance
     * @throws DependencyManagerException On error
     */
    public function getDependency(string $class):object
    {
        throw new DependencyManagerException(errorType: DependencyManagerError::DEPENDENCY_NOT_FOUND);
    }

    /**
     * @template T Callable return type
     * Call a callable by injecting the dependencies
     * @param callable():T $toCall Callable
     * @param array $manualParameters Parameters to inject manually
     * @return T Callable return type
     * @throws DependencyManagerException On error
     */
    public function call(Callable $toCall,array $manualParameters = []):mixed
    {
        throw new DependencyManagerException(errorType: DependencyManagerError::DEPENDENCY_NOT_FOUND);
    }

    /**
     * Register a singleton dependency builder
     * @param string $class Class name
     * @param Closure $builder Dependency builder (The non-specified builder parameters will be injected automatically if required)
     * @param array $manualParameters Parameters to inject manually
     * @return $this
     */
    public function registerSingleton(string $class,Closure $builder,array $manualParameters = []): DependencyManager
    {
        return $this;
    }

    /**
     * Register a dependency builder
     * @param string $class Class name
     * @param Closure $builder Dependency builder (The non-specified builder parameters will be injected automatically if required)
     * @param array $manualParameters Parameters to inject manually
     * @attention The build closure will be called each time this dependency is requested
     * @return $this
     */
    public function register(string $class,Closure $builder,array $manualParameters = []): DependencyManager
    {
        return $this;
    }

    /**
     * @return DependencyManager Dependency manager instance
     */
    public static function get(): DependencyManager
    {
        if(self::$instance === null)
        {
            self::$instance = new DependencyManager();
        }

        return self::$instance;
    }
}