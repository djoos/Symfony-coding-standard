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
 * Checks whether variables are properly compared to expressions.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class YodaConditionsSniff implements Sniff
{
    private $yodas = array(
        T_IS_EQUAL,
        T_IS_IDENTICAL,
        T_IS_NOT_EQUAL,
        T_IS_NOT_IDENTICAL,
    );

    /**
     * Registers the tokens that this sniff wants to listen for.
     */
    public function register()
    {
        return array(
            T_IF,
            T_ELSEIF,
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
        $opener = $tokens[$stackPtr]['parenthesis_opener'];
        $closer = $tokens[$stackPtr]['parenthesis_closer'];

        do {
            $comparison = $phpcsFile->findNext($this->yodas, $opener + 1, $closer);

            if (false === $comparison) {
                break;
            }

            if (T_VARIABLE === $tokens[$comparison - 2]['code'] && T_VARIABLE !== $tokens[$comparison + 2]['code']) {
                $error = 'Use Yoda conditions when checking a variable against an expression to avoid an accidental assignment inside the condition statement';
                $phpcsFile->addError($error, $stackPtr, 'Invalid');
            }

            $opener = $comparison + 1;

        } while ($opener < $closer);
    }

}
