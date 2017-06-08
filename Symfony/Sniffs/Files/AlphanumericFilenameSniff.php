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

namespace Symfony\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether filename contains any other character than alphanumeric
 * and underscores.
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   wicliff wolda <wicliff.wolda@gmail.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */
class AlphanumericFilenameSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);
    }

    /**
     * Process.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int                         $stackPtr
     *
     * @return int|void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $filename = $phpcsFile->getFilename();

        if ($filename === 'STDIN') {
            return;
        }

        $filename = str_replace('_', '', basename($filename, '.php'));

        if (false === ctype_alnum($filename)) {
            $error = sprintf('Filename "%s" contains non alphanumeric characters', $filename);
            $phpcsFile->addError($error, $stackPtr, 'Invalid');
            $phpcsFile->recordMetric($stackPtr, 'Alphanumeric filename', 'no');
        } else {
            $phpcsFile->recordMetric($stackPtr, 'Alphanumeric filename', 'yes');
        }

        // Ignore the rest of the file.
        return ($phpcsFile->numTokens + 1);
    }

}
