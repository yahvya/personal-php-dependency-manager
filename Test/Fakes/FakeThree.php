<?php

namespace Test\Fakes;

use Yahay\PhpDependencyInjector\DependencyManager;

class FakeThree
{
    public function __construct(
        protected(set) DependencyManager $dependencyManager
    )
    {

    }
}