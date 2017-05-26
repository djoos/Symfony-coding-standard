<?php
/**
 * This file is part of the Symfony2-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer-Symfony2
 * @author   Thomas BEAUJEAN
 * @license  http://spdx.org/licenses/MIT MIT License
 * @version  GIT: master
 * @link     https://github.com/escapestudios/Symfony2-coding-standard
 */

/**
 * Symfony2_Sniffs_Constant_SelfSniff.
 *
 * Throws warnings if the self is used instead of static
 *
 * @category PHP
 * @package  PHP_CodeSniffer-Symfony2
 * @author   Thomas BEAUJEAN
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/escapestudios/Symfony2-coding-standard
 */
class Symfony2_Sniffs_Constant_SelfSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_SELF);

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addError(
           'Please use STATIC instead of SELF',
            $stackPtr,
            'Invalid'
        );
    }//end process()

}//end class

