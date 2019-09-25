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
class Bar
{
    public function doStuff()
    {
        $var1 = 'foo';
        $var2 = 'bar';

        $anonClass = new class($var1, $var2)
        {
            private $property;

            public function thisIsAfterTheProperty()
            {
            }
        };
    }
}
class MoreBar
{
    public function doStuff()
    {
        $var1 = 'foo';
        $var2 = 'bar';

        $anonClass = new class($var1, $var2)
        {
            public function thisShouldBeAfterTheProperty()
            {
            }

            private $property;
        };
    }
}
