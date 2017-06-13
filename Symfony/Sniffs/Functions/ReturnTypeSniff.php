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

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether a function returns the appropriate return type
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class ReturnTypeSniff implements Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     */
    public function register()
    {
        return [
            T_FUNCTION,
        ];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The PHP_CodeSniffer file where the
     *                                               token was found.
     * @param int                         $stackPtr  The position in the PHP_CodeSniffer
     *                                               file's token stack where the token
     *                                               was found.
     *
     * @return void|int Optionally returns a stack pointer. The sniff will not be
     *                  called again on the current file until the returned stack
     *                  pointer is reached. Return (count($tokens) + 1) to skip
     *                  the rest of the file.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $type = isset($tokens[$stackPtr]['scope_opener']) ? $phpcsFile->findNext(T_RETURN_TYPE, $stackPtr + 1, $tokens[$stackPtr]['scope_opener']) : false;

        if (false !== $type && 'void' === strtolower($tokens[$type]['content'])) {
            $next = $stackPtr;

            do {
                if (false === $next = $phpcsFile->findNext(T_RETURN, $next + 1, $tokens[$stackPtr]['scope_closer'])) {
                    break;
                }

                if (T_SEMICOLON !== $tokens[$next + 1]['code']) {
                    $phpcsFile->addError(
                        'Use return null; when a function explicitly returns null values and use return; when the function returns void values',
                        $next,
                        'Invalid'
                    );
                }
            } while (true);
        }
    }

}
