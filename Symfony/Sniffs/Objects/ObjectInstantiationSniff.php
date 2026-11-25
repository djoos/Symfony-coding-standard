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

namespace Symfony\Sniffs\Objects;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * ObjectInstantiationSniff.
 *
 * Throws a warning if an object isn't instantiated using parenthesis.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   Authors <Symfony-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony-coding-standard
 */
class ObjectInstantiationSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_NEW,
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
        $allowed = array(
            T_STRING,
            T_NS_SEPARATOR,
            T_NAME_FULLY_QUALIFIED,
            T_NAME_QUALIFIED,
            T_VARIABLE,
            T_STATIC,
            T_SELF,
            T_DOUBLE_COLON,
            T_OBJECT_OPERATOR,
            T_OPEN_SQUARE_BRACKET,
            T_CLOSE_SQUARE_BRACKET,
        );

        $object = $stackPtr;
        $line   = $tokens[$object]['line'];

        $anonClass = $this->findAnonClass($phpcsFile, $stackPtr);

        if (false !== $anonClass) {
            $next = $phpcsFile->findNext(
                Tokens::EMPTY_TOKENS,
                $anonClass + 1,
                null,
                true
            );

            if (false === $next || $tokens[$next]['code'] !== T_OPEN_PARENTHESIS) {
                $phpcsFile->addError(
                    'Use parentheses when instantiating classes',
                    $stackPtr,
                    'Invalid'
                );
            }
            return;
        }

        while ($object && $tokens[$object]['line'] === $line) {
            $object = $phpcsFile->findNext($allowed, $object + 1);

            if ($tokens[$object]['line'] === $line
                && !in_array($tokens[$object + 1]['code'], $allowed, true)
            ) {
                if ($tokens[$object + 1]['code'] !== T_OPEN_PARENTHESIS) {
                    $phpcsFile->addError(
                        'Use parentheses when instantiating classes',
                        $stackPtr,
                        'Invalid'
                    );
                }

                break;
            }
        }
    }

    /**
     * Returns the position of the anonymous class token instantiated by the
     * given T_NEW token, skipping attributes, modifiers and comments.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the T_NEW token.
     *
     * @return int|false
     */
    protected function findAnonClass(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $skip   = Tokens::EMPTY_TOKENS;
        $skip[T_READONLY] = T_READONLY;

        $next = $phpcsFile->findNext($skip, $stackPtr + 1, null, true);

        while (false !== $next) {
            if ($tokens[$next]['code'] === T_ATTRIBUTE
                && isset($tokens[$next]['attribute_closer'])
            ) {
                $next = $phpcsFile->findNext(
                    $skip,
                    $tokens[$next]['attribute_closer'] + 1,
                    null,
                    true
                );
                continue;
            }

            if ($tokens[$next]['code'] === T_ANON_CLASS) {
                return $next;
            }

            break;
        }

        return false;
    }
}
