<?php
/*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
* A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
* OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
* LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
* DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
* THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
* OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* This software consists of voluntary contributions made by many individuals
* and is licensed under the MIT license. For more information, see
* <http://www.doctrine-project.org>.
*/

/**
 * Copies a directory recursively.
 *
 * @param string $source The source path to copy.
 * @param string $target The target path to copy to.
 */
function copyDirectory($source, $target)
{
    /** @var $iterator \RecursiveIteratorIterator|\RecursiveDirectoryIterator */
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        /** @var $file SplFileInfo */
        if ($file->isDir()) {
            mkdir($target . '/' . $iterator->getSubPathName());
        } else {
            copy($file, $target . '/' . $iterator->getSubPathName());
        }
    }
}

/**
 * Removes a directory recursively.
 *
 * @param string $directory The path to the directory to remove.
 */
function removeDirectory($directory)
{
    /** @var $iterator \RecursiveIteratorIterator|\RecursiveDirectoryIterator */
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        /** @var $file SplFileInfo */
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }

    rmdir($directory);
}

$phpCodeSnifferDir = __DIR__ . '/vendor/squizlabs/php_codesniffer';

if ( ! file_exists($phpCodeSnifferDir)) {
    throw new \RuntimeException(
        'Could not find PHP_CodeSniffer dependency. ' .
        'Did you maybe forget to run "php composer.phar install --prefer-source --dev"?'
    );
}

$sniffTestSuiteFile = $phpCodeSnifferDir . '/tests/Standards/AllSniffs.php';

if ( ! file_exists($sniffTestSuiteFile)) {
    throw new \RuntimeException(
        'Could not find PHP_CodeSniffer test suite. ' .
        'Did you maybe forget to run composer installation with option "--prefer-source"?'
    );
}

require_once __DIR__ . '/vendor/autoload.php';

$SymfonyStandardDir = $phpCodeSnifferDir . '/src/Standards/Symfony';

if (file_exists($SymfonyStandardDir)) {
    removeDirectory($SymfonyStandardDir);
}

mkdir($SymfonyStandardDir);
mkdir($SymfonyStandardDir . '/Sniffs');
mkdir($SymfonyStandardDir . '/Tests');
copy(__DIR__ . '/Symfony/ruleset.xml', $SymfonyStandardDir . '/ruleset.xml');

copyDirectory(__DIR__ . '/Symfony/Sniffs', $SymfonyStandardDir . '/Sniffs');
copyDirectory(__DIR__ . '/Symfony/Tests', $SymfonyStandardDir . '/Tests');
