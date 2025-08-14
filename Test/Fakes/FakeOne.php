<?php

namespace Test\Fakes;

class FakeOne
{
    public function __construct(
        protected(set) FakeTwo $fakeTwo
    )
    {}
}