<?php

namespace Test\Src;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesNamespace;
use PHPUnit\Framework\TestCase;
use Test\Fakes\FakeFive;
use Test\Fakes\FakeFour;
use Test\Fakes\FakeIOne;
use Test\Fakes\FakeITwo;
use Test\Fakes\FakeOne;
use Yahay\PhpDependencyInjector\BuilderDataDto;
use Yahay\PhpDependencyInjector\DependencyManager;
use Yahay\PhpDependencyInjector\DependencyManagerException;

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

    #[TestDox(text: "Test that the getDependency method provide the right instance")]
    public function testGetDependencyMethodProvideTheRightInstance():void
    {
        $dependencyManager = new DependencyManager();

        $dependencyManager
            ->dependenciesMap
            ->register(
                class: FakeFive::class,
                builderData: new BuilderDataDto(
                    builder: fn (string $s,?int $i) => new FakeFive(),
                    manualParameters: ["s" => "lib"]
                )
            )
            ->registerSingleton(
                class: FakeFour::class,
                builderData: new BuilderDataDto(
                    builder: fn () => new FakeFour(fakeIOneAndFakeITwo: new FakeFive())
                )
            );

        try
        {
            $instance = $dependencyManager->getDependency(class: FakeFour::class);

            $this->assertInstanceOf(
                expected: FakeFour::class,
                actual: $instance,
                message: "The getDependency method should return the right instance"
            );

            $instance = $dependencyManager->getDependency(class: FakeFive::class);

            $this->assertInstanceOf(
                expected: FakeFive::class,
                actual: $instance,
                message: "The getDependency method should return the right instance"
            );
        }
        catch(Exception $e)
        {
            $this->fail(message: "The getDependency method should not throw an exception <{$e->getMessage()}>");
        }
    }

    #[TestDox(text: "Test that the getDependency method return different instance for simple registered methods")]
    public function testGetDependencyMethodReturnDifferentInstanceForSimpleRegisteredMethods():void
    {
        $dependencyManager = new DependencyManager();

        $dependencyManager
            ->dependenciesMap
            ->register(
                class: FakeFive::class,
                builderData: new BuilderDataDto(
                    builder: fn () => new FakeFive()
                )
            );

        try
        {
            $instance = $dependencyManager->getDependency(class: FakeFive::class);
            $secondInstance = $dependencyManager->getDependency(class: FakeFive::class);

            $this->assertNotSame(
                expected: $instance,
                actual: $secondInstance,
                message: "The getDependency method should return different instance"
            );
        }
        catch(Exception $e)
        {
            $this->fail(message: "The getDependency method should not throw an exception <{$e->getMessage()}>");
        }
    }

    #[TestDox(text: "Test that the getDependency method return same instance for singleton registered methods")]
    public function testGetDependencyMethodReturnSameInstanceForSingletonRegisteredMethods():void
    {
        $dependencyManager = new DependencyManager();

        $dependencyManager
            ->dependenciesMap
            ->registerSingleton(
                class: FakeFive::class,
                builderData: new BuilderDataDto(
                    builder: fn () => new FakeFive()
                )
            );

        try
        {
            $instance = $dependencyManager->getDependency(class: FakeFive::class);
            $secondInstance = $dependencyManager->getDependency(class: FakeFive::class);

            $this->assertSame(
                expected: $instance,
                actual: $secondInstance,
                message: "The getDependency method should return the same instance"
            );
        }
        catch(Exception $e)
        {
            $this->fail(message: "The getDependency method should not throw an exception <{$e->getMessage()}>");
        }
    }

    #[TestDox(text: "Test that the getDependency method throw an exception for un registered classes")]
    public function testGetDependencyMethodThrowAnExceptionForUnRegisteredClasses():void
    {
        $this->expectException(exception: DependencyManagerException::class);

        $dependencyManager = new DependencyManager();

        $dependencyManager->getDependency(class: FakeOne::class);
    }

    #[TestDox(text: "Test that the call method throw an exception for un registered classes")]
    public function testCallMethodThrowAnExceptionForUnRegisteredClasses():void
    {
        $this->expectException(exception: DependencyManagerException::class);

        $dependencyManager = new DependencyManager();

        $dependencyManager->call(toCall: fn(FakeFour $f) => null);
    }

    #[TestDox(text: "Test that the call method effectively call the function")]
    public function testCallMethodEffectivelyCallTheFunction():void
    {
        $dependencyManager = new DependencyManager();

        $dependencyManager
            ->dependenciesMap
            ->register(
                class: FakeFive::class,
                builderData: new BuilderDataDto(
                    builder: fn () => new FakeFive()
                )
            );

        try
        {
            $this->expectOutputString(expectedString: 'called 1'. PHP_EOL . 'called 2');

            $dependencyManager->call(toCall: fn() => print("called 1"));

            echo PHP_EOL;

            $dependencyManager->call(toCall: fn(FakeIOne&FakeITwo $f) => print("called 2"));
        }
        catch(Exception $e)
        {
            $this->fail(message: "The call method should not throw an exception <{$e->getMessage()}>");
        }
    }
}