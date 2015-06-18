<?php

//bad
new Foo;
new Foo\Bar;
new \Foo\Bar;
new $foo;
new Foo ();

//good
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
