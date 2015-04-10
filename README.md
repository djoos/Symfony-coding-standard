Symfony2 PHP CodeSniffer Coding Standard
========================================

A code standard to check against the [Symfony coding standards](http://symfony.com/doc/current/contributing/code/standards.html), originally shamelessly copied from the -disappeared- opensky/Symfony2-coding-standard repository.

Installation
------------

1. Install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

2. Copy, symlink or check out this repository

        git clone git://github.com/escapestudios/Symfony2-coding-standard.git Symfony2-coding-standard

---OR---

2.1. [Install Composer](https://getcomposer.org/doc/00-intro.md)

2.2. Create a composer.json file which contains:

    {
        "require-dev": {
            "escapestudios/symfony2-coding-standard": "~2.0"
        }
    }

2.3. Install dev dependencies

    composer update --dev

3. Done!

        phpcs --standard=/path/to/standard /path/to/code

Contributing
------------

If you do contribute code to these sniffs, please make sure it conforms to the PEAR
coding standard and that the Symfony2-coding-standard unit tests still pass.

To check the coding standard, run from the Symfony2-coding-standard source root:

    $ phpcs --ignore=*/tests/* --standard=PEAR . -n

The unit-tests are run from within the PHP_CodeSniffer directory:

    $ phpunit --filter Symfony2_* tests/AllTests.php
