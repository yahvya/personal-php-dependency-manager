<?php

namespace Yahay\PhpDependencyInjector;

use Closure;

/**
 * Builder data DTO
 */
class BuilderDataDto
{
    /**
     * @var object|null Build result
     */
    public ?object $buildResult = null;

    /**
     * @param Closure $builder Builder closure
     * @param array $manualParameters Manual parameters to inject to the builder closure
     */
    public function __construct(
        public Closure $builder,
        public array $manualParameters = []
    ) {}
}