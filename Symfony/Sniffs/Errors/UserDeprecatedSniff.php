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

namespace Symfony\Sniffs\Errors;


use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether E_USER_DEPRECATED errors are silenced by default.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class UserDeprecatedSniff implements Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_STRING,
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

        if ($tokens[$stackPtr]['content'] !== 'trigger_error') {
            return;
        }

        $pos = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);
        $opener = $tokens[$pos]['parenthesis_opener'];
        $closer = $tokens[$pos]['parenthesis_closer'];

        do {
            $string = $phpcsFile->findNext(T_STRING, $opener, $closer);

            if (false === $string) {
                break;
            }

            $opener = $string + 1;

            if ($tokens[$string]['content'] !== 'E_USER_DEPRECATED') {
                continue;
            }

            if ('@' === $tokens[$stackPtr -1]['content']) {
                continue;
            }

            if ('@' === $tokens[$stackPtr - 2]['content']
                && 'T_NS_SEPARATOR' === $tokens[$stackPtr - 1]['type']
            ) {
                continue;
            }

            $error = 'Calls to trigger_error with type E_USER_DEPRECATED ';
            $error .= 'must be switched to opt-in via @ operator';

            $phpcsFile->addError($error, $stackPtr, 'Invalid');

            break;
        } while ($opener < $closer);
    }

}
