[![Build Status](https://secure.travis-ci.org/escapestudios/Symfony2-coding-standard.png)](http://travis-ci.org/escapestudios/Symfony2-coding-standard)

Symfony2 PHP CodeSniffer Coding Standard
========================================

A code standard to check against the [Symfony coding standards](http://symfony.com/doc/current/contributing/code/standards.html), originally shamelessly copied from the -disappeared- opensky/Symfony2-coding-standard repository.

Installation
------------
### Globally

1. Install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

2. Clone this repository

        $ git clone git://github.com/escapestudios/Symfony2-coding-standard.git
        
3. Make the Symfony2 standard available to ``phpcs`` using one of the following options  
  a) Copy or symlink the contained Symfony2 directory to the ``phpcs`` 'Standards' directory  

        $ cd path/to/Standards
        $ ln -s /path/to/clone/Symfony2-coding-standards/Symfony2 Symfony2
        
  b) Add the cloned project to ``phpcs`` ``installed_paths``

        $ phpcs --config-show installed_paths
        Array
        (
            [installed_paths] => /usr/local/etc/php-code-sniffer/Standards
        )
        $ phpcs --config-set installed_paths /usr/local/etc/php-code-sniffer/Standards/,/path/to/project/clone/
        
     Note the usage of the previous ``installed_paths``. Do not forget this, or it will just be overwritten.

### As Part of a Composer Project


1. [Install Composer](https://getcomposer.org/doc/00-intro.md) if you don't already have it.  
1.1. If this is a non Composer project, initiate it with ``composer init``. This will create a ``composer.json`` flie. 

2. Add the ``espacestudios/symfony2-coding-standards`` dependency. This wil install the Symfony2 coding standard, along with a project-local ``phpcs`` binary in ``./bin``

        $ composer require --dev escapestudios/symfony2-coding-standards:~2.0

3. Add the Symfony2 coding standard to the PHP_CodeSniffer install path. This path is relative to the ``phpcs`` home directory. For a default composer project this will be under ./vendor/squizlabs/php_codesniffer, so ``../../escapestudios`` is literally ``./vendor/escapestudios/``

        $ bin/phpcs --config-set installed_paths ../../escapestudios/symfony2-coding-standard/

4. Done! You will want to use the local ``bin/phpcs`` for checking this project's source, rather than trying to use the global ``phpcs`` (which may or may not exist)

General Usage
-------------
        $ phpcs --standard=Symfony2 /path/to/code

Usage as Part of a Symfony2 Project
-----------------------------------
So you want to use this standard as part of a Symfony2 project? _"Easy!"_ you say, _"it IS the Symfony2 coding standard. It will just work"_.

Well..."yes"...with a "but".

The Symfony2 project doesn't always conform to the Symfony2 coding standards. Furthermore, there are directories you may not want to check on your project (such as the ``./vendors`` directory) If you want to use this standard on a Symfony2 project without getting false errors and warnings from code your project didn't author, you still can. Copy the following template to ``app/ruleset.xml``

    <?xml version="1.0"?>
    <ruleset name="Project">
       <description>The Symfony2 project ruleset</description>
    
       <rule ref="Symfony2">
       </rule>
    
       <!-- Project exclusions -->
    
       <!-- There should not be any code in the bundle Resources directory. -->
       <exclude-pattern>*/Resources/*</exclude-pattern>
       <exclude-pattern>*.css</exclude-pattern>
       <exclude-pattern>*.scss</exclude-pattern>
       <exclude-pattern>*.js</exclude-pattern>
    
       <!-- We don't want to test the vendor code -->
       <exclude-pattern type="relative">^vendor/*</exclude-pattern>
       <exclude-pattern type="relative">^bin/*</exclude-pattern>
       <!-- No need to process the cache or logs -->
       <exclude-pattern type="relative">^app/cache/*</exclude-pattern>
       <exclude-pattern type="relative">^app/logs/*</exclude-pattern>
    
       <!-- These don't conform, but we neither wrote them, or are ever going to touch them -->
       <exclude-pattern type="relative">^web/config.php</exclude-pattern>
       <exclude-pattern type="relative">^app/check.php</exclude-pattern>
       <exclude-pattern type="relative">^app/SymfonyRequirements.php</exclude-pattern>
    
       <!-- These don't have namespaces, but we didn't write them -->
       <rule ref="PSR1.Classes.ClassDeclaration.MissingNamespace">
           <exclude-pattern>app/AppCache.php</exclude-pattern>
           <exclude-pattern>app/AppKernel.php</exclude-pattern>
       </rule>
    
       <!-- Has a require_once, and a class, so it breaks this rule -->
       <!-- We don't care -->
       <rule ref="PSR1.Files.SideEffects.FoundWithSymbols">
           <exclude-pattern>app/AppCache.php</exclude-pattern>
       </rule>
    
    </ruleset>
    
That's it! Commit this file to your repository. Now, instead of specifying ``--standard=Symfony2`` as a flag to ``phpcs``, use ``--standard=app/ruleset.xml``.

e.g.

    $ bin/phpcs --standard=app/ruleset.xml ./

Or, if you want to avoid having to use this flag all together, you can set this ruleset to be the default ruleset used for this repository.

    $ bin/phpcs --config-set default_standard app/ruleset.xml
    $ bin/phpcs ./ # Analyze all the things!
