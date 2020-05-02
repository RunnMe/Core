<?php

namespace Runn\tests\Di\Container;

use PHPUnit\Framework\TestCase;
use Runn\Core\Std;
use Runn\Di\Container;
use Runn\Di\ContainerEntryNotFoundException;
use Runn\Di\ContainerException;

class testWithoutConstructor {}
class testWithConstructorWithoutArgs {
    public function __construct() {
    }
}

class Foo {}
class Bar {}

class testWithDependencies {
    public $foo;
    public $bar;
    public function __construct(Foo $foo, Bar $bar, Baz $baz = null) {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
    }
}

class testWithUndefinedDependency {
    public $foo;
    public $bar;
    public function __construct(Baz $baz) {
        $this->baz = $baz;
    }
}

class Foo111 {}
class Foo11 {
    public $foo111;
    public function __construct(Foo111 $foo111)
    {
        $this->foo111 = $foo111;
    }
}
class Foo1 {
    public $foo11;
    public function __construct(Foo11 $foo11)
    {
        $this->foo11 = $foo11;
    }
}

class ContainerResolveTest extends TestCase
{


    public function testResolveInvalidId()
    {
        try {
            $container = new Container();
            $res = $container->resolve('test');
        } catch (ContainerEntryNotFoundException $e) {
            $this->assertSame('test', $e->getId());
            return;
        }
        $this->fail();
    }


    public function testResolveWithException()
    {
        $exception = new \Exception;
        try {
            $container = new Container();
            $container->set('test', function () use ($exception) {
                throw $exception;
            });
            $res = $container->resolve('test');
        } catch (ContainerException $e) {
            $this->assertSame($exception, $e->getPrevious());
            return;
        }
        $this->fail();
    }


    public function testSetGet()
    {
        $container = new Container();
        $container->set('test', function () {return 42;});
        $this->assertSame(42, $container->resolve('test'));
    }

    public function testSingleton()
    {
        $container = new Container();
        $container->singleton('test', function () {return new Std(['foo' => 'bar']);});

        $obj1 = $container->resolve('test');
        $this->assertEquals(new Std(['foo' => 'bar']), $obj1);

        $obj2 = $container->resolve('test');
        $this->assertEquals(new Std(['foo' => 'bar']), $obj2);

        $this->assertSame($obj1, $obj2);
    }

    public function testResolveExistingClassWithoutConstructor()
    {
        $container = new Container();

        $res1 = $container->resolve(testWithoutConstructor::class);
        $this->assertEquals(new testWithoutConstructor(), $res1);

        $res2 = $container->resolve(testWithoutConstructor::class);
        $this->assertEquals(new testWithoutConstructor(), $res2);

        $this->assertSame($res1, $res2);
    }

    public function testResolveExistingClassWithConstructorWithoutArgs()
    {
        $container = new Container();

        $res1 = $container->resolve(testWithConstructorWithoutArgs::class);
        $this->assertEquals(new testWithConstructorWithoutArgs(), $res1);

        $res2 = $container->resolve(testWithConstructorWithoutArgs::class);
        $this->assertEquals(new testWithConstructorWithoutArgs(), $res2);

        $this->assertSame($res1, $res2);
    }

    public function testResolveWithDependecies()
    {
        $container = new Container();

        $res1 = $container->resolve(testWithDependencies::class);
        $this->assertInstanceOf(testWithDependencies::class, $res1);
        $this->assertEquals(new Foo(), $res1->foo);
        $this->assertEquals(new Bar(), $res1->bar);
        $this->assertNull($res1->baz);

        $res2 = $container->resolve(testWithDependencies::class);
        $this->assertSame($res1, $res2);
    }

    public function testResolveUndefinedDependecy()
    {
        try {
            $container = new Container();
            $res = $container->resolve(testWithUndefinedDependency::class);
        } catch (ContainerEntryNotFoundException $e) {
            $this->assertSame(__NAMESPACE__ . '\\Baz', $e->getId());
            return;
        }
        $this->fail();
    }

    public function testResolveNestedDependecies()
    {
        $container = new Container();

        $res1 = $container->resolve(Foo1::class);
        $this->assertInstanceOf(Foo1::class, $res1);
        $this->assertEquals(new Foo11(new Foo111()), $res1->foo11);
        $this->assertEquals(new Foo111, $res1->foo11->foo111);

        $res2 = $container->resolve(Foo1::class);
        $this->assertSame($res1, $res2);
    }

}
