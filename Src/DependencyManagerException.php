<?php

namespace Yahay\PhpDependencyInjector;

use Exception;
use Throwable;

/**
 * Dependency manager error exception type
 */
class DependencyManagerException extends Exception
{
    /**
     * @param DependencyManagerError $errorType Error type
     * @param Throwable $e Initial exception
     */
    public function __construct(
        protected(set) DependencyManagerError $errorType,
        protected(set) Throwable $e
    )
    {
        parent::__construct(message: "A dependency manager error has occurred.");
    }
}