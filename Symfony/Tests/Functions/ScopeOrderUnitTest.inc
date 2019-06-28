<?php

class Foo
{
    private function bar(){}

    protected function baz(){}

    public function qux(){}
}
new class()
{
    private function bar(){}

    protected function baz(){}

    public function qux(){}
};
class Factory
{
    private function createObject()
    {
        return new class()
        {
            public function foo(){}
        };
    }
}

