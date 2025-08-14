<?php

namespace Yahay\PhpDependencyInjector;

/**
 * Dependency manager error type
 */
enum DependencyManagerError
{
    case DEPENDENCY_NOT_FOUND;
    case DEPENDENCY_BUILD_FAIL;
}
