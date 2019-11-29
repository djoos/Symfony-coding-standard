<?php
/**
 * This file is part of the Symfony-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer-Symfony
 * @author   wicliff wolda <dev@bloody-wicked.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @version  GIT: master
 * @link     https://github.com/djoos/Symfony-coding-standard
 */

namespace Symfony\Sniffs\Arrays;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * MultiLineArrayCommaSniff.
 *
 * Throws warnings if the last item in a multi line array does not have a
 * trailing comma
 *
 * @category PHP
 * @package  PHP_CodeSniffer-Symfony
 * @author   wicliff wolda <dev@bloody-wicked.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony-coding-standard
 */
class MultiLineArrayCommaSniff implements Sniff
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
        return array(
                T_ARRAY,
                T_OPEN_SHORT_ARRAY,
               );

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
        $open   = $tokens[$stackPtr];

        if ($open['code'] === T_ARRAY) {
            $closePtr = $open['parenthesis_closer'];
        } else {
            $closePtr = $open['bracket_closer'];
        }

        if ($open['line'] !== $tokens[$closePtr]['line']) {
            $arrayIsNotEmpty = $phpcsFile->findPrevious(
                array(
                    T_WHITESPACE,
                    T_COMMENT,
                    T_ARRAY,
                    T_OPEN_PARENTHESIS,
                    T_OPEN_SHORT_ARRAY,
                ),
                $closePtr - 1,
                $stackPtr,
                true
            );
            if ($arrayIsNotEmpty !== false) {
                $lastCommaPtr = $phpcsFile->findPrevious(
                    T_COMMA,
                    $closePtr,
                    $stackPtr
                );
                while ($lastCommaPtr < $closePtr -1) {
                    $lastCommaPtr++;

                    if ($tokens[$lastCommaPtr]['code'] !== T_WHITESPACE
                        && $tokens[$lastCommaPtr]['code'] !== T_PHPCS_IGNORE
                        && $tokens[$lastCommaPtr]['code'] !== T_COMMENT
                    ) {
                        $fix = $phpcsFile->addFixableError(
                            'Add a comma after each item in a multi-line array',
                            $stackPtr,
                            'Invalid'
                        );

                        if ($fix === true) {
                            $ptr = $phpcsFile->findPrevious(
                                array(T_WHITESPACE, T_COMMENT, T_PHPCS_IGNORE),
                                $closePtr-1,
                                $stackPtr,
                                true
                            );

                            $phpcsFile->fixer->addContent($ptr, ',');
                            $phpcsFile->fixer->endChangeset();
                        }

                        break;
                    }
                }
            }
        }
    }

}
