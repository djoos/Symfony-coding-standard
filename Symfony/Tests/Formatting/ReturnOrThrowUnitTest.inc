<?php

function foo(){
    if ($a === $b) {
        return false;
    } else {
        return true;
    }
}

function bar()
{
    if ($a == $b) {
        return false;
    }

    return true;
}

function baz()
{
    if ($a == $b) {
        $a = function () {
            return false;
        };
    } else {
        return true;
    }
}