<?php

namespace Test\Src;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesNamespace;
use PHPUnit\Framework\TestCase;
use Yahay\PhpDependencyInjector\DependencyManager;

/**
 * Dependency manager test class
 */
#[CoversClass(className: DependencyManager::class)]
#[UsesNamespace(namespace: "Yahay\PhpDependencyInjector")]
class DependencyManagerTest extends TestCase
{
    #[TestDox(text: "Test that the '::get' method returns the same between calls")]
    public function testGetMethodReturnTheSameInstance():void
    {
        $dependencyManagerInitialInstance = DependencyManager::get();

        $this->assertSame(
            expected: $dependencyManagerInitialInstance,
            actual: DependencyManager::get(),
            message: "The provided instance by the ::get method is not the same"
        );
    }
}