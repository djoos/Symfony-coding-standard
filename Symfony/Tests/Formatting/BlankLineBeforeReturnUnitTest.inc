<?php
function validFunctionReturnOne()
{
    return;
}

function validFunctionReturnTwo()
{

    return;
}

function validFunctionReturnThree()
{
    echo "";

    return;
}

function validFunctionReturnFour()
{
    // comment
    return;
}

function validFunctionReturnFive()
{
    /**
     * multi-line
     */
    return;
}

function invalidFunctionReturnOne()
{
    echo "";
    return;
}

function validFunctionReturnWithPhpcsIgnore1(): string
{
    // phpcs:ignore Foobar.Whatever.Rule
    return foobar(1);
}

function validFunctionReturnWithPhpcsIgnore2(): string
{
    /* phpcs:ignore Foobar.Whatever.Rule */
    return foobar(1);
}

function validFunctionReturnWithPhpcsIgnore3(): string
{
    /** phpcs:ignore Foobar.Whatever.Rule */
    return foobar(1);
}

function validFunctionReturnWithVariableDoc(): string
{
    /** @psalm-var ClassName $variable */
    return foobar(1);
}

switch ($condition) {
    case 'foo':
        return true;

    default:
        return false;
}
