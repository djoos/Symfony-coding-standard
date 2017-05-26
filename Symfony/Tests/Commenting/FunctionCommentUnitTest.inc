<?php

/**
 * @param string $test Test argument
 */
function test($test)
{
    return 42;
}

/**
 * @param string $test Test argument
 *
 * @return int
 */
function test($test)
{
    return 42;
}

/**
 * @param array $tab Test argument
 */
function testWithCallBack($tab)
{
    $thing = array_map(function ($t) {
        return $t[0];
    }, $tab);
}
