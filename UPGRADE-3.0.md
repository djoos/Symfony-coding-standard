UPGRADE FROM 2.x TO 3.0
-----------------------

Table of contents
- [Standard](#standard)
- [Ruleset](#ruleset)
- [Warnings](#warnings)

Standard
--------
In version 3.0 we changed the standards name from ``Symfony2`` to ``Symfony`` as this standard is not bound to any version of Symfony.
Make sure you set your standard accordingly:
```
phpcs --config-set default_standard Symfony
```

Ruleset
-------
In version 3.0 we changed the standards name from ``Symfony2`` to ``Symfony`` as this standard is not bound to any version of Symfony.
If you are using a custom ``ruleset.xml`` or standalone sniffs from this repository, this has to be changed accordingly.

Before:
```xml
<rule ref="Symfony2.Commenting.FunctionComment.MissingParamComment">
        // ..
</rule>
```

After:
```xml
<rule ref="Symfony.Commenting.FunctionComment.MissingParamComment">
    // ...
</rule>
```

Warnings
--------
Both the ``The license block has to be present at the top of every PHP file, before the namespace`` and the ``Always use identical comparison unless you need type juggling`` messages will appear as warnings as these sniffs are unable to determine whether or not it's an error.
In order to suppress those warnings one can get rid of them with: 
 ```
 phpcs --config-set show_warnings 0
 ```