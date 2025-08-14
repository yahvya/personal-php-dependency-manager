<?php

namespace Yahay\PhpDependencyInjector;

/**
 * Dependencies manager
 * @notice Pseudo singleton class. You can use it like that, or you can use DependencyManager::get() which will return the same instance. The constructor is opened
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

    public function __construct()
    {
        $this->dependenciesMap = new DependencyMap();
    }

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