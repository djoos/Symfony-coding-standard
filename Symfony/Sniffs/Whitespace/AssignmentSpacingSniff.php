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

namespace Symfony\Sniffs\Whitespace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * AssignmentSpacingSniff.
 *
 * Throws warnings if an assignment operator isn't surrounded with whitespace.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   Authors <Symfony-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony-coding-standard
 */
class AssignmentSpacingSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                  );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return Tokens::$assignmentTokens;

    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (($tokens[$stackPtr - 1]['code'] !== T_WHITESPACE
            || $tokens[$stackPtr + 1]['code'] !== T_WHITESPACE)
            && $tokens[$stackPtr - 1]['content'] !== 'strict_types'
        ) {
            $fix = $phpcsFile->addFixableError(
                'Add a single space around assignment operators',
                $stackPtr,
                'Invalid'
            );

            if ($fix === true) {
                $replacement = $tokens[$stackPtr]['content'];
                if ($tokens[$stackPtr - 1]['code'] !== T_WHITESPACE) {
                    $replacement = ' '.$replacement;
                }

                if ($tokens[$stackPtr + 1]['code'] !== T_WHITESPACE) {
                    $replacement .= ' ';
                }

                $phpcsFile->fixer->replaceToken($stackPtr, $replacement);
            }
        }
    }
}
