<?php

namespace Yahay\PhpDependencyInjector;

/**
 * Dependencies manager
 * @notice Pseudo singleton class. You can use it like that, or you can use DependencyManager::get() which will return the same instance. The constructor is opened
 * @attention Be aware of circular dependencies
 * @attention You should use types while coding
 */
class DependencyManager
{
    /**
     * @var DependencyManager|null Manager instance
     */
    protected static ?DependencyManager $instance = null;

    /**
     * @var DependencyMap Dependencies map
     */
    protected(set) DependencyMap $dependenciesMap;

    /**
     * @var DependencyBuilder Internal dependency builder
     */
    protected(set) DependencyBuilder $dependencyBuilder;

    public function __construct()
    {
        $this->dependenciesMap = new DependencyMap();
        $this->dependencyBuilder = new DependencyBuilder(dependencyManager: $this);
    }

    /**
     * Provide a registered dependency
     * @param string $class Class name
     * @return object The build instance
     * @throws DependencyManagerException On error
     */
    public function getDependency(string $class):object
    {
        return $this;
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
        return null;
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