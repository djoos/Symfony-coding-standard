[![Build Status](https://secure.travis-ci.org/escapestudios/Symfony2-coding-standard.png)](http://travis-ci.org/escapestudios/Symfony2-coding-standard)

Symfony2 PHP CodeSniffer Coding Standard
========================================

A code standard to check against the [Symfony coding standards](http://symfony.com/doc/current/contributing/code/standards.html), originally shamelessly copied from the -disappeared- opensky/Symfony2-coding-standard repository.

Installation
------------

1. Install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

2. Copy, symlink or check out this repository

        git clone git://github.com/escapestudios/Symfony2-coding-standard.git Symfony2

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

3. Add Symfony2 to the PHP_CodeSniffer install path 

    phpcs --config-set installed_paths /path/to/Symfony2

4. Done!

        phpcs /path/to/code