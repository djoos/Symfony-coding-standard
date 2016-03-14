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

/**
 * Throws errors if multiple classes are defined in a single file.
 *
 * PHP version 5
 *
 * Symfony coding standard specifies: "Define one class per file;"
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@escapestudios.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/escapestudios/Symfony2-coding-standard
 */
class Symfony2_Sniffs_Classes_MultipleClassesOneFileSniff
    implements PHP_CodeSniffer_Sniff
{
    /**
     * The number of times the T_CLASS token is encountered in the file.
     *
     * @var int
     */
    protected $classCount = 0;

    /**
     * The current file this class is operating on.
     *
     * @var string
     */
    protected $currentFile;

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
        return array(T_CLASS);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->currentFile !== $phpcsFile->getFilename()) {
            $this->classCount  = 0;
            $this->currentFile = $phpcsFile->getFilename();
        }

        $this->classCount++;

        if ($this->classCount > 1) {
            $phpcsFile->addError(
                'Multiple classes defined in a single file',
                $stackPtr
            );
        }

        return;
    }
}
