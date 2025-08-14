<?php

namespace Src;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yahay\PhpDependencyInjector\BuilderDataDto;
use Yahay\PhpDependencyInjector\DependencyMap;

/**
 * Dependency map test class
 */
#[CoversClass(className: DependencyMap::class)]
#[CoversClass(className: BuilderDataDto::class)]
class DependencyMapTest extends TestCase
{
    #[TestDox(text: "Test that the registerSingleton method effectively store the expected data")]
    public function testRegisterSingletonEffectivelyStoreTheData():void
    {
        $dependencyMap = new DependencyMap();

        $builderData = new BuilderDataDto(
            builder: function(){},
            manualParameters: ["param"]
        );

        $builderData->buildResult = function(){};

        $dependencyMap->registerSingleton(
            class: DependencyMapTest::class,
            builderData: $builderData
        );

        $this->assertArrayHasKey(
            key: DependencyMapTest::class,
            array: $dependencyMap->singletons,
            message: "The class is not registered"
        );

        $this->assertSame(
            expected: $builderData,
            actual: $dependencyMap->singletons[DependencyMapTest::class],
            message: "The provided data is not the same"
        );
    }

    #[TestDox(text: "Test that the register method effectively store the expected data")]
    public function testRegisterEffectivelyStoreTheData():void
    {
        $dependencyMap = new DependencyMap();

        $builderData = new BuilderDataDto(
            builder: function(){},
            manualParameters: ["param1"]
        );

        $dependencyMap->register(
            class: DependencyMapTest::class,
            builderData: $builderData
        );

        $this->assertArrayHasKey(
            key: DependencyMapTest::class,
            array: $dependencyMap->classics,
            message: "The class is not registered"
        );

        $this->assertSame(
            expected: $builderData,
            actual: $dependencyMap->classics[DependencyMapTest::class],
            message: "The provided data is not the same"
        );
    }
}