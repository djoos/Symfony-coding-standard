UPGRADE FROM 2.x TO 3.0
-----------------------

Table of contents
- [Standard](#standard)
- [Ruleset](#ruleset)

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