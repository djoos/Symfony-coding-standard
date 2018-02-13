<?php
/**
 * Bootstrap file for PHP_CodeSniffer Symfony Coding Standard unit tests.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony-coding-standard
 * @author   Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony-coding-standard
 */

$myStandardName = 'Symfony';

require_once __DIR__.'/vendor/squizlabs/php_codesniffer/tests/bootstrap.php';

// Add this Standard
PHP_CodeSniffer\Config::setConfigData(
    'installed_paths',
    __DIR__.DIRECTORY_SEPARATOR.'Symfony',
    true
);

// Ignore all other Standards in tests
$standards   = PHP_CodeSniffer\Util\Standards::getInstalledStandards();
$standards[] = 'Generic';

$ignoredStandardsStr = implode(
    ',', array_filter(
        $standards, function ($v) use ($myStandardName) {
            return $v !== $myStandardName;
        }
    )
);

putenv("PHPCS_IGNORE_TESTS={$ignoredStandardsStr}");
