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
 * Checks for proper type hinting.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class TypeHintingSniff implements Sniff
{
    /**
     * Blacklisted types
     *
     * @var array<string, string>
     */
    private static $_blacklist = [
        'boolean' => 'bool',
        'integer' => 'int',
        'double' => 'float',
        'real' => 'float',
    ];

    /**
     * Cast tokens
     *
     * @var array
     */
    private static $_casts = [
        T_BOOL_CAST,
        T_INT_CAST,
        T_DOUBLE_CAST,
    ];

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_DOC_COMMENT_TAG,
            T_BOOL_CAST,
            T_INT_CAST,
            T_DOUBLE_CAST,
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
        $tag = $tokens[$stackPtr];

        $fixPtr = $stackPtr;

        if ('@var' === $tag['content']) {
            $type = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $stackPtr + 1);
            $fixPtr = $type;
            $hint = strtolower(
                preg_replace(
                    '/([^\s]+)[\s]+.*/',
                    '$1',
                    $tokens[$type]['content']
                )
            );
        } elseif (in_array($tag['code'], self::$_casts, true)) {
            $hint = strtolower(
                preg_replace(
                    '/\(([^\s]+)\)/',
                    '$1',
                    $tag['content']
                )
            );
        }

        if (isset($hint, self::$_blacklist[$hint])) {
            $error = sprintf(
                'For type-hinting in PHPDocs and casting, use %s instead of %s',
                self::$_blacklist[$hint],
                $hint
            );

            $fixable = $phpcsFile->addFixableError($error, $stackPtr, 'Invalid');

            if (true === $fixable) {

                if ($fixPtr === $stackPtr) {
                    $fixedContent = self::$_blacklist[$hint];
                    $fixedContent = "({$fixedContent})";
                } else {
                    $fixedContent = $tokens[$fixPtr]['content'];
                    $fixedContent = preg_replace(
                        "/^$hint/",
                        self::$_blacklist[$hint],
                        $fixedContent
                    );
                }

                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken($fixPtr, $fixedContent);
                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
