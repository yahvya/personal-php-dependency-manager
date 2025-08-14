<?php

namespace Test\Src;

use Exception;
use Test\Fakes\FakeFive;
use Test\Fakes\FakeFour;
use PHPUnit\Framework\Attributes\TestDox;
use Test\Fakes\FakeThree;
use Test\Fakes\FakeTwo;
use Test\Fakes\FakeOne;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesNamespace;
use PHPUnit\Framework\TestCase;
use Yahay\PhpDependencyInjector\BuilderDataDto;
use Yahay\PhpDependencyInjector\DependencyBuilder;
use Yahay\PhpDependencyInjector\DependencyManager;
use Yahay\PhpDependencyInjector\DependencyManagerException;

/**
 * Dependency builder test class
 */
#[CoversClass(className: DependencyBuilder::class)]
#[UsesNamespace(namespace: "Yahay\PhpDependencyInjector")]
class DependencyBuilderTest extends TestCase
{
    private DependencyBuilder $dependencyBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $dependencyManager = new DependencyManager();

        $dependencyManager
            ->dependenciesMap
            ->register(
                class: FakeOne::class,
                builderData: new BuilderDataDto(
                    builder: fn(FakeTwo $two) => new FakeOne(fakeTwo: $two)
                )
            )
            ->register(
                class: FakeFive::class,
                builderData: new BuilderDataDto(
                    builder: fn() => new FakeFive()
                )
            )
            ->registerSingleton(
                class: FakeTwo::class,
                builderData: new BuilderDataDto(
                    builder: fn(FakeThree $fakeThree,FakeFour $fakeFour) => new FakeTwo(fakeThreeOrFakeFour: $fakeThree,fakeFour: $fakeFour)
                )
            );

        $this->dependencyBuilder = new DependencyBuilder(dependencyManager: $dependencyManager);
    }

    #[TestDox(text: "Test that the build method return the right instance")]
    public function testBuildMethodReturnTheRightInstance():void
    {
        try
        {
            $result = $this->dependencyBuilder->build(
                builderDataDto: $this->dependencyBuilder->dependencyManager->dependenciesMap->classics[FakeOne::class]
            );

            $this->assertInstanceOf(
                expected: FakeOne::class,
                actual: $result,
                message: "The build method should return the right instance"
            );
        }
        catch (Exception $e)
        {
            $this->fail(message: "The build method should not throw an exception <{$e->getMessage()}>");
        }
    }

    #[TestDox(text: "Test that the build method throw an exception")]
    public function testBuildMethodThrowAnException():void
    {
        $this->expectException(DependencyManagerException::class);

        $this->dependencyBuilder->build(
            builderDataDto: new BuilderDataDto(
                builder: fn(string $builtInType) => null
            )
        );
    }
}