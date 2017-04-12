[![Build Status](https://secure.travis-ci.org/djoos/Symfony2-coding-standard.png)](http://travis-ci.org/djoos/Symfony2-coding-standard)

# Symfony2 PHP CodeSniffer Coding Standard

A coding standard to check against the [Symfony coding standards](http://symfony.com/doc/current/contributing/code/standards.html), originally shamelessly copied from the -disappeared- opensky/Symfony2-coding-standard repository.

## Installation

### Composer

This standard can be installed with the [Composer](https://getcomposer.org/) dependency manager.

1. [Install Composer](https://getcomposer.org/doc/00-intro.md)

2. Install the coding standard as a dependency of your project

        composer require --dev escapestudios/symfony2-coding-standard:~2.0

3. Add the coding standard to the PHP_CodeSniffer install path

        vendor/bin/phpcs --config-set installed_paths vendor/escapestudios/symfony2-coding-standard

4. Check the installed coding standards for "Symfony2"

        vendor/bin/phpcs -i

5. Done!

        vendor/bin/phpcs /path/to/code

### Stand-alone

1. Install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

2. Checkout this repository 

        git clone git://github.com/djoos/Symfony2-coding-standard.git

3. Add the coding standard to the PHP_CodeSniffer install path

        phpcs --config-set installed_paths /path/to/Symfony2-coding-standard

   Or copy/symlink this repository's "Symfony2"-folder inside the phpcs `Standards` directory

4. Check the installed coding standards for "Symfony2"

        phpcs -i

5. Done!

        phpcs /path/to/code
