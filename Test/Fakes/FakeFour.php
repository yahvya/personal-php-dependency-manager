<?php

namespace Test\Fakes;

class FakeFour
{
    public function __construct(
        protected(set) FakeIOne&FakeITwo $fakeIOneAndFakeITwo
    )
    {}
}