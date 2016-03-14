<?php

/**
 * This file is part of the Symfony2-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@escapestudios.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/escapestudios/Symfony2-coding-standard
 */

if (class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception(
        'Class PHP_CodeSniffer_Standards_AbstractScopeSniff not found'
    );
}

/**
 * Verifies that class members have scope modifiers.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@escapestudios.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class Symfony2_Sniffs_Scope_MethodScopeSniff
    extends PHP_CodeSniffer_Standards_AbstractScopeSniff
{
    /**
     * Constructs a Symfony2_Sniffs_Scope_MethodScopeSniff.
     */
    public function __construct()
    {
        parent::__construct(array(T_CLASS), array(T_FUNCTION));

    }

    /**
     * Processes the function tokens within the class.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     * @param int                  $currScope The current scope opener token.
     *
     * @return void
     */
    protected function processTokenWithinScope(
        PHP_CodeSniffer_File $phpcsFile,
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
            PHP_CodeSniffer_Tokens::$scopeModifiers,
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
}
