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

namespace Symfony\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether there are (else)if or break statements after return or throw
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class ReturnOrThrowSniff implements Sniff
{
    private $openers = [
        T_IF,
        T_CASE,
    ];

    private $conditions = [
        T_ELSEIF,
        T_ELSE,
        T_BREAK,
    ];

    /**
     * Registers the tokens that this sniff wants to listen for.
     */
    public function register()
    {
        return [
            T_THROW,
            T_RETURN,
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
        $elem = $tokens[$stackPtr];

        // find the function we're currently in and an if or case statement
        $function = $phpcsFile->findPrevious(T_FUNCTION, $stackPtr);

        if (false === $function) {
            return;
        }

        $opener = $phpcsFile->findPrevious($this->openers, $stackPtr, $tokens[$function]['scope_opener']);

        // check whether the return / throw is within a if or case statement
        if ($opener && $elem['line'] <= $tokens[$tokens[$opener]['scope_closer']]['line']) {
            // check whether there's an elseif / else / break following the if or case statement
            $condition = $phpcsFile->findNext($this->conditions, $stackPtr + 1, $tokens[$function]['scope_closer']);

            if (false !== $condition){
                if (T_CASE === $tokens[$opener]['code']) {
                    $next = $phpcsFile->findNext([T_CASE, T_DEFAULT], $stackPtr + 1, $tokens[$function]['scope_closer']);

                    $err = (false === $next) ? true : $tokens[$condition]['line'] < $tokens[$next]['line'];
                } else {
                    $err = $tokens[$condition]['line'] === $tokens[$tokens[$opener]['scope_closer']]['line'];
                }

                if ($err) {
                    $phpcsFile->addError(
                        'Do not use else, elseif, break after if and case conditions which return or throw something',
                        $stackPtr,
                        'Invalid'
                    );
                }
            }
        }
    }

}