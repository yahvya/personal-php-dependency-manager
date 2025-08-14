<?php

namespace Test\Fakes;

class FakeTwo
{
    public function __construct(
        protected(set) FakeThree|FakeFour $fakeThreeOrFakeFour,
        protected(set) FakeFour $fakeFour
    )
    {}
}