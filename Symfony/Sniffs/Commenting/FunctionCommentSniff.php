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
use PHP_CodeSniffer\Standards\PEAR\Sniffs\Commenting\FunctionCommentSniff as Sniff;

/**
 * Symfony standard customization to PEARs FunctionCommentSniff.
 *
 * Verifies that :
 * <ul>
 *   <li>
 *     There is a &#64;return tag if a return statement exists inside the method
 *   </li>
 * </ul>
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   Authors <Symfony-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class FunctionCommentSniff extends Sniff
{
    /**
     * Process the return comment of this function comment.
     *
     * @param File $phpcsFile    The file being scanned.
     * @param int  $stackPtr     The position of the current token
     *                           in the stack passed in $tokens.
     * @param int  $commentStart The position in the stack
     *                           where the comment started.
     *
     * @return void
     */
    protected function processReturn(
        File $phpcsFile,
        $stackPtr,
        $commentStart
    ) {

        if ($this->isInheritDoc($phpcsFile, $stackPtr)) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Only check for a return comment if a non-void return statement exists
        if (isset($tokens[$stackPtr]['scope_opener'])) {
            // Start inside the function
            $start = $phpcsFile->findNext(
                T_OPEN_CURLY_BRACKET,
                $stackPtr,
                $tokens[$stackPtr]['scope_closer']
            );
            for ($i = $start; $i < $tokens[$stackPtr]['scope_closer']; ++$i) {
                // Skip closures
                if ($tokens[$i]['code'] === T_CLOSURE) {
                    $i = $tokens[$i]['scope_closer'];
                    continue;
                }

                // Found a return not in a closure statement
                // Run the check on the first which is not only 'return;'
                if ($tokens[$i]['code'] === T_RETURN
                    && $this->isMatchingReturn($tokens, $i)
                ) {
                    parent::processReturn($phpcsFile, $stackPtr, $commentStart);
                    break;
                }
            }
        }

    }

    /**
     * Is the comment an inheritdoc?
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return boolean True if the comment is an inheritdoc
     */
    protected function isInheritDoc(File $phpcsFile, $stackPtr)
    {
        $start = $phpcsFile->findPrevious(T_DOC_COMMENT_OPEN_TAG, $stackPtr - 1);
        $end = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, $start);

        $content = $phpcsFile->getTokensAsString($start, $end - $start);

        return
            preg_match('#({@inheritdoc}|(?<!{)@inheritdoc(?!}))#i', $content) === 1;
    }

    /**
     * Process the function parameter comments.
     *
     * @param File $phpcsFile    The file being scanned.
     * @param int  $stackPtr     The position of the current token
     *                           in the stack passed in $tokens.
     * @param int  $commentStart The position in the stack
     *                           where the comment started.
     *
     * @return void
     */
    protected function processParams(
        File $phpcsFile,
        $stackPtr,
        $commentStart
    ) {
        if ($this->isInheritDoc($phpcsFile, $stackPtr)) {
            return;
        }

        parent::processParams($phpcsFile, $stackPtr, $commentStart);
    }

    /**
     * Is the return statement matching?
     *
     * @param array $tokens    Array of tokens
     * @param int   $returnPos Stack position of the T_RETURN token to process
     *
     * @return boolean True if the return does not return anything
     */
    protected function isMatchingReturn($tokens, $returnPos)
    {
        do {
            $returnPos++;
        } while ($tokens[$returnPos]['code'] === T_WHITESPACE);

        return $tokens[$returnPos]['code'] !== T_SEMICOLON;
    }
}
