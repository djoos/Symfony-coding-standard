<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme;

use Other\Qux;

/**
 * Coding standards demonstration.
 */
class FooBar
{
    const SOME_CONST = 42;

    /**
     * @var string
     */
    private $fooBar;

    private $qux;

    /**
     * @param string $dummy Some argument description
     * @param Qux    $qux   This line is not in the example
     */
    public function __construct($dummy, Qux $qux)
    {
        $this->fooBar = $this->transformText($dummy);
        $this->qux = $qux;
    }

    /**
     * @return string
     *
     * @deprecated
     */
    public function someDeprecatedMethod()
    {
        trigger_deprecation('symfony/package-name', '5.1', 'The %s() method is deprecated, use Acme\Baz::someMethod() instead.', __METHOD__);

        return Baz::someMethod();
    }

    /**
     * Transforms the input given as first argument.
     *
     * @param bool|string $dummy   Some argument description
     * @param array       $options An options collection to be used within the transformation
     *
     * @return string|null The transformed input
     *
     * @throws \RuntimeException When an invalid option is provided
     */
    private function transformText($dummy, array $options = [])
    {
        $defaultOptions = [
            'some_default' => 'values',
            'another_default' => 'more values',
        ];

        foreach ($options as $name => $value) {
            if (!array_key_exists($name, $defaultOptions)) {
                throw new \RuntimeException(sprintf('Unrecognized option "%s"', $name));
            }
        }

        $mergedOptions = array_merge(
            $defaultOptions,
            $options
        );

        if (true === $dummy) {
            return 'something';
        }

        if (is_string($dummy)) {
            if ('values' === $mergedOptions['some_default']) {
                return substr($dummy, 0, 5);
            }

            return ucwords($dummy);
        }

        return null;
    }

    /**
     * Performs some basic operations for a given value.
     *
     * @param mixed $value     Some value to operate against
     * @param bool  $theSwitch Some switch to control the method's flow
     */
    private function performOperations($value = null, $theSwitch = false)
    {
        if (!$theSwitch) {
            return;
        }

        $this->qux->doFoo($value);
        $this->qux->doBar($value);
    }
}
