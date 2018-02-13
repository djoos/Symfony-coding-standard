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
 * Checks for the presence of a license comment prior to the namespace.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class LicenseSniff implements Sniff
{

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_NAMESPACE
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
        if (false === $phpcsFile->findPrevious(
            [T_COMMENT, T_DOC_COMMENT_OPEN_TAG],
            $stackPtr
        )
        ) {
            $phpcsFile->addWarning(
                'The license block has to be present at the top
                of every PHP file, before the namespace',
                $stackPtr,
                'Warning'
            );
        }
    }
}
