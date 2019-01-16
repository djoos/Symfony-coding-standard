<?php

function foo(): void
{
    return null;
}

function test(): void
{
    $t = function (): int {
        return 1;
    };
}

function omg(): void
{
    $c = new class
    {
        function foo(): void
        {
            return null;
        }

        function bar(): void
        {
            $t = function (): int {
                return 1;
            };
        }
    };
}

interface FooInterface
{
     public function fooMixed();

     public function voidFoo(): void;
}

abstract class AbstractFoo
{
    public function fooMixed();

    public function voidFoo(): void;

    public function fail(): void
    {
        return null;
    }
}

