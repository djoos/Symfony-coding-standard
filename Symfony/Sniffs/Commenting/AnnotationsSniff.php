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
 * Checks whether annotations of a different type are separated with newlines.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class AnnotationsSniff implements Sniff
{
    const PATTERN = '/^@([^\\\(]+).*$/i';

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
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
        $closer = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, $stackPtr);

        if (false !== $next = $phpcsFile->findNext(
            $this->register(),
            $stackPtr + 1,
            $closer
        )
        ) {
            $first = preg_replace(
                self::PATTERN,
                '$1',
                $tokens[$stackPtr]['content']
            );
            $second = preg_replace(
                self::PATTERN,
                '$1',
                $tokens[$next]['content']
            );

            $stackPtrLine = $tokens[$stackPtr]['line'];
            $nextLine = $tokens[$next]['line'];

            if ($first !== $second && $stackPtrLine + 2 > $nextLine) {
                $error = 'Group annotations together ';
                $error .= 'so that annotations of the same type ';
                $error .= 'immediately follow each other, and annotations ';
                $error .= 'of a different type are separated ';
                $error .= 'by a single blank line';

                $fixable = $phpcsFile->addFixableError(
                    $error,
                    $stackPtr,
                    'Invalid'
                );

                if (true === $fixable) {
                    $indentPtr = $phpcsFile->findFirstOnLine(
                        T_DOC_COMMENT_WHITESPACE,
                        $next
                    );
                    $indentStr = $phpcsFile->getTokensAsString($indentPtr, 1);
                    $content   = "\n{$indentStr}*";
                    $phpcsFile->fixer->beginChangeset();
                    $phpcsFile->fixer->addContentBefore($next - 1, $content);
                    $phpcsFile->fixer->endChangeset();
                }
            }
        }
    }
}
