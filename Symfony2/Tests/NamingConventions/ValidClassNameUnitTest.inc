<?php

// Abstract Class Names

abstract class BadAbstractClassName {}

abstract class AbstractTest {}

class BadTest extends AbstractTest {}

// Trait Class Names

trait BadTraitName {}

trait GoodTrait {}

// Interface Class Names

interface BadInterfaceName {}

interface GoodInterface {}

interface BadInterfaceChild extends BadInterfaceName {}

interface GoodChildInterface extends GoodInterface {}

// Exception Class Names

class BaseException {}

class GoodChildException extends BaseException {}

class BadExceptionChild extends BaseException {}
