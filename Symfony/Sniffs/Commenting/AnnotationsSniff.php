<?php


namespace Symfony\Sniffs\Commenting;


use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class AnnotationsSniff implements Sniff
{

    private static $pattern = '/^@([^\/]+)[\/]?*$/i';

    /**
     * @inheritDoc
     */
    public function register()
    {
        return [
            T_DOC_COMMENT_TAG,
        ];
    }

    /**
     * @inheritDoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $closer = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, $stackPtr);

        if (false !== $next = $phpcsFile->findNext($this->register(), $stackPtr + 1, $closer)) {
            $first = explode('\\', $tokens[$stackPtr]['content'])[0];
            $second = explode('\\', $tokens[$next]['content'])[0];

            if ($first !== $second && $tokens[$stackPtr]['line'] + 2 !== $tokens[$next]['line']) {
                $phpcsFile->addError(
                    'Group annotations together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line',
                    $stackPtr,
                    'Invalid'
                );
            }
        }
    }
}
