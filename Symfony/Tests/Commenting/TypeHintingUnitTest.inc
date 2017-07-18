<?php

class Foo
{
    /**
     * @var boolean $a
     */
    protected $a;

    /**
     * @var integer $b
     */
    protected $b;

    public function __construct()
    {
        $a = (boolean) 0;
        $b = (integer) $a;

        $a = (bool) $b;
        $b = (int) $a;
    }

}

