<?php

/**
 * This file is part of the Symfony-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   Authors <Symfony-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony-coding-standard
 */

namespace Symfony\Sniffs\Functions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * ScopeOrderSniff.
 *
 * Throws warnings if properties are declared after methods
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   Authors <Symfony-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony-coding-standard
 */
class ScopeOrderSniff implements Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_CLASS,
            T_INTERFACE,
            T_ANON_CLASS,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $function = $stackPtr;

        $scopes = array(
            0 => T_PUBLIC,
            1 => T_PROTECTED,
            2 => T_PRIVATE,
        );

        $whitelisted = array(
            '__construct',
            'setUp',
            'tearDown',
        );

        while ($function) {
            $end = null;

            if (isset($tokens[$stackPtr]['scope_closer'])) {
                $end = $tokens[$stackPtr]['scope_closer'];
            }

            $function = $phpcsFile->findNext(
                array(
                    T_ANON_CLASS,
                    T_FUNCTION,
                ),
                $function + 1,
                $end
            );

            if (T_ANON_CLASS === $tokens[$function]['code']) {
                $function = $tokens[$function]['scope_closer'];
                continue;
            }

            if (isset($tokens[$function]['parenthesis_opener'])) {
                $scope = $phpcsFile->findPrevious($scopes, $function -1, $stackPtr);
                $name = $phpcsFile->findNext(
                    T_STRING,
                    $function + 1,
                    $tokens[$function]['parenthesis_opener']
                );

                if ($scope
                    && $name
                    && !in_array(
                        $tokens[$name]['content'],
                        $whitelisted,
                        true
                    )
                ) {
                    $current = array_keys($scopes,  $tokens[$scope]['code'], true);
                    $current = $current[0];

                    $error = 'Declare public methods first,'
                    .'then protected ones and finally private ones';

                    if (isset($previous) && $current < $previous) {
                        $phpcsFile->addError(
                            $error,
                            $scope,
                            'Invalid'
                        );
                    }

                    $previous = $current;
                }
            }
        }
    }

}
