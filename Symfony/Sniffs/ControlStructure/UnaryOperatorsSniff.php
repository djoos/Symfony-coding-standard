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

namespace Symfony\Sniffs\ControlStructure;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether unary operators are adjacent to the affected variable.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class UnaryOperatorsSniff implements Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_INC,
            T_DEC,
        );
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param File $phpcsFile The file where the token was found.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void|int Optionally returns a stack pointer. The sniff will not be
     *                  called again on the current file until the returned stack
     *                  pointer is reached. Return (count($tokens) + 1) to skip
     *                  the rest of the file.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (T_CLASS === $tokens[$stackPtr + 1]['code']
            || T_SELF === $tokens[$stackPtr + 1]['code']
        ) {
            return;
        }

        if ((T_VARIABLE !== $tokens[$stackPtr - 1]['code']
            && T_VARIABLE !== $tokens[$stackPtr + 1]['code'])
            && (T_OBJECT_OPERATOR !== $tokens[$stackPtr - 2]['code'])
            && (']' !== $tokens[$stackPtr - 1]['content'])
        ) {
            $error = 'Place unary operators adjacent to the affected variable';
            $phpcsFile->addError($error, $stackPtr, 'Invalid');
        }
    }

}
