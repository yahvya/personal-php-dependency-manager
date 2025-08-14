<?php

namespace Yahay\PhpDependencyInjector;

use Exception;

/**
 * Dependency manager error exception type
 */
class DependencyManagerException extends Exception
{
    /**
     * @param DependencyManagerError $errorType Error type
     */
    public function __construct(protected(set) DependencyManagerError $errorType)
    {
        parent::__construct(message: "A dependency manager error has occurred.");
    }
}