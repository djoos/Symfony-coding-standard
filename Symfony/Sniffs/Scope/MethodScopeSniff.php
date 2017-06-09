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

use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verifies that class members have scope modifiers.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   Authors <Symfony-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class MethodScopeSniff extends AbstractScopeSniff
{
    /**
     * Constructs a MethodScopeSniff.
     */
    public function __construct()
    {
        parent::__construct(array(T_CLASS), array(T_FUNCTION));

    }

    /**
     * Processes the function tokens within the class.
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int  $stackPtr  The position where the token was found.
     * @param int  $currScope The current scope opener token.
     *
     * @return void
     */
    protected function processTokenWithinScope(
        File $phpcsFile,
        $stackPtr,
        $currScope
    ) {
        $tokens = $phpcsFile->getTokens();

        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        if ($methodName === null) {
            // Ignore closures.
            return;
        }

        $modifier = $phpcsFile->findPrevious(
            Tokens::$scopeModifiers,
            $stackPtr
        );

        if (($modifier === false)
            || ($tokens[$modifier]['line'] !== $tokens[$stackPtr]['line'])
        ) {
            $error = 'No scope modifier specified for function "%s"';
            $data  = array($methodName);
            $phpcsFile->addError($error, $stackPtr, 'Missing', $data);
        }

    }

    /**
     * Process tokens outside scope.
     *
     * @param File $phpcsFile
     * @param int  $stackPtr
     */
    protected function processTokenOutsideScope(File $phpcsFile, $stackPtr)
    {
    }
}
