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

namespace Symfony\Sniffs\Classes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * PropertyDeclarationSniff.
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
class PropertyDeclarationSniff implements Sniff
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
            T_ANON_CLASS
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
            T_PRIVATE,
            T_ANON_CLASS
        );

        while ($scope) {
            if (T_ANON_CLASS === $tokens[$scope]['code']) {
                $scope = $tokens[$scope]['scope_closer'];
                continue;
            }
            $scope = $phpcsFile->findNext(
                $wantedTokens,
                $scope + 1,
                $end
            );

            if ($scope && $tokens[$scope + 2]['code'] === T_VARIABLE
                && $tokens[$scope]['code'] !== T_ANON_CLASS
            ) {
                $phpcsFile->addError(
                    'Declare class properties before methods',
                    $scope,
                    'Invalid'
                );
            }
        }
    }

}
