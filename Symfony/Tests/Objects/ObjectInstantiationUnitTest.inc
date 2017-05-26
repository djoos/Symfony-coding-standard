<?php

// bad
new Foo;
new Foo\Bar;
new \Foo\Bar;
new $foo;
new Foo ();
new static;
new self;

// good
new Foo();
new foo();
new Foo(true);
new Foo($this->foo);
new Foo\Bar();
new Foo\Bar(true);
new Foo\Bar($this->foo);
new \Foo\Bar();
new \Foo\Bar(true);
new \Foo\Bar($this->foo);
new $foo();
new $foo(true);
new $foo($this->foo);
new static();
new static(true);
new static($this->foo);
new self();
new self(true);
new self($this->foo);
