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
 * Symfony2_Sniffs_Classes_PropertyDeclarationSniff.
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
class Symfony2_Sniffs_Classes_PropertyDeclarationSniff
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

        $end = null;
        if (isset($tokens[$stackPtr]['scope_closer'])) {
            $end = $tokens[$stackPtr]['scope_closer'];
        }

        $scope = $phpcsFile->findNext(
            T_FUNCTION,
            $stackPtr,
            $end
        );

        $wantedTokens = array(
            T_PUBLIC,
            T_PROTECTED,
            T_PRIVATE
        );

        while ($scope) {
            $scope = $phpcsFile->findNext(
                $wantedTokens,
                $scope + 1,
                $end
            );

            if ($scope && $tokens[$scope + 2]['code'] === T_VARIABLE) {
                $phpcsFile->addError(
                    'Declare class properties before methods',
                    $scope,
                    'Invalid'
                );
            }
        }
    }

}
