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
     *
     * @return array
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

        $methodProperties = $phpcsFile->getMethodProperties($stackPtr);

        $type = $methodProperties['return_type_token'];

        if (false === $type || 'void' !== strtolower($tokens[$type]['content'])) {
            return;
        }

        $next = $stackPtr;

        do {
            if (!isset($tokens[$stackPtr]['scope_closer'])) {
                break;
            }

            $next = $phpcsFile->findNext(
                T_RETURN,
                $next + 1,
                $tokens[$stackPtr]['scope_closer']
            );

            if (false === $next) {
                break;
            }

            $conditions = $tokens[$next]['conditions'];
            $lastScope  = key(array_slice($conditions, -1, null, true));

            if ($stackPtr !== $lastScope) {
                continue;
            }

            if (T_SEMICOLON !== $tokens[$next + 1]['code']) {
                $error = 'Use return null; when a function explicitly ';
                $error .= 'returns null values and use return; ';
                $error .= 'when the function returns void values';

                $phpcsFile->addError(
                    $error,
                    $next,
                    'Invalid'
                );
            }
        } while (true);
    }

}
