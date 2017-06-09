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
    private static $blacklist = [
        'boolean' => 'bool',
        'integer' => 'int',
        'double' => 'float',
        'real' => 'float',
    ];

    private static $casts = [
        T_BOOL_CAST,
        T_INT_CAST,
        T_DOUBLE_CAST,
    ];

    /**
     * Registers the tokens that this sniff wants to listen for.
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
        $tag = $tokens[$stackPtr];

        if ('@var' === $tag['content']) {
            $type = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $stackPtr + 1);
            $hint = strtolower(preg_replace('/([^\s]+)[\s]+.*/', '$1', $tokens[$type]['content']));
        } elseif (in_array($tag['code'], self::$casts)) {
            $hint = strtolower(preg_replace('/\(([^\s]+)\)/', '$1', $tag['content']));
        }

        if (isset($hint) && isset(self::$blacklist[$hint])) {
            $error = sprintf('For type-hinting in PHPDocs and casting, use %s instead of %s', self::$blacklist[$hint], $hint);

            $phpcsFile->addError($error, $stackPtr, 'Invalid');
        }
    }
}
