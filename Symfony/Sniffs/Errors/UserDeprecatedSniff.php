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

            if ($tokens[$string]['content'] === 'E_USER_DEPRECATED' && '@' !== $tokens[$stackPtr -1]['content']) {
                $error = 'Calls to trigger_error with type E_USER_DEPRECATED must be switched to opt-in via @ operator';
                $phpcsFile->addError($error, $stackPtr, 'Invalid');

                break;
            } else {
                $opener = $string + 1;
            }
        } while ($opener < $closer);
    }

}
