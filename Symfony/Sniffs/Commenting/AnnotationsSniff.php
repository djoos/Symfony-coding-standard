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

namespace Symfony\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether annotations of a different type are seperated with newlines.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class AnnotationsSniff implements Sniff
{

    private static $pattern = '/^@([^\\\(]+).*$/i';

    /**
     * Registers the tokens that this sniff wants to listen for.
     */
    public function register()
    {
        return [
            T_DOC_COMMENT_TAG,
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
        $closer = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, $stackPtr);

        if (false !== $next = $phpcsFile->findNext($this->register(), $stackPtr + 1, $closer)) {
            $first = preg_replace(self::$pattern, '$1', $tokens[$stackPtr]['content']);
            $second = preg_replace(self::$pattern, '$1', $tokens[$next]['content']);

            if ($first !== $second && $tokens[$stackPtr]['line'] + 2 > $tokens[$next]['line']) {
                $phpcsFile->addError(
                    'Group annotations together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line',
                    $stackPtr,
                    'Invalid'
                );
            }
        }
    }
}
