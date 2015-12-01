<?php

/**
 * @param string $test
 */
function test($test)
{
    return 42;
}

/**
 * @param string $test
 *
 * @return int
 */
function test($test)
{
    return 42;
}

/**
 * @param array $tab
 */
function testWithCallBack($tab)
{
    $thing = array_map(function ($t) {
        return $t[0];
    }, $tab);
}
