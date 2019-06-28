<?php

class Foo
{
    public function __construct()
    {
    }

    private $bar;
}
new class()
{
    public function __construct()
    {
    }

    private $property;
};
class Factory
{
    public function createObject()
    {
        return new class()
        {
            private $property;
        };
    }
}
