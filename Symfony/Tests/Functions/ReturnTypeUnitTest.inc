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
