<?php

/**
 * This file is part of the Symfony2-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@escapestudios.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/escapestudios/Symfony2-coding-standard
 */

/**
 * Symfony2_Sniffs_Functions_ScopeOrderSniff.
 *
 * Throws warnings if properties are declared after methods
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@escapestudios.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/escapestudios/Symfony2-coding-standard
 */
class Symfony2_Sniffs_Functions_ScopeOrderSniff
    implements PHP_CodeSniffer_Sniff
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
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
                T_FUNCTION,
                $function + 1,
                $end
            );

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
                        $whitelisted
                    )
                ) {
                    $current = array_keys($scopes,  $tokens[$scope]['code']);
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
