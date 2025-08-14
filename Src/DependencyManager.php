<?php

namespace Yahay\PhpDependencyInjector;

/**
 * Dependencies manager. Singleton
 */
class DependencyManager
{
    /**
     * @var DependencyManager|null Manager instance
     */
    protected static ?DependencyManager $instance = null;

    /**
     * Singleton protection
     */
    protected function __construct()
    {
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