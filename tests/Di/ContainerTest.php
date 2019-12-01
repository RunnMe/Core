<?php

namespace Runn\tests\Di\Container;

use PHPUnit\Framework\TestCase;
use Runn\Core\Std;
use Runn\Di\Container;
use Runn\Di\ContainerEntryNotFoundException;
use Runn\Di\ContainerException;

class ContainerTest extends TestCase
{

    public function testSetInvalidArgument1()
    {
        $container = new Container();

        $this->expectException(\TypeError::class);
        $container->set('id', 'foo');
    }

    public function testSetInvalidArgument2()
    {
        $container = new Container();

        $this->expectException(\TypeError::class);
        $container->id = 'foo';
    }

    public function testSetInvalidArgument3()
    {
        $container = new Container();

        $this->expectException(\TypeError::class);
        $container['id'] = 'foo';
    }

    public function testGetInvalidId1()
    {
        try {
            $container = new Container();
            $res = $container->get('test');
        } catch (ContainerEntryNotFoundException $e) {
            $this->assertSame('test', $e->getId());
            return;
        }
        $this->fail();
    }

    public function testGetInvalidId2()
    {
        try {
            $container = new Container();
            $res = $container->test;
        } catch (ContainerEntryNotFoundException $e) {
            $this->assertSame('test', $e->getId());
            return;
        }
        $this->fail();
    }

    public function testGetInvalidId3()
    {
        try {
            $container = new Container();
            $res = $container['test'];
        } catch (ContainerEntryNotFoundException $e) {
            $this->assertSame('test', $e->getId());
            return;
        }
        $this->fail();
    }

    public function testGetWithException1()
    {
        $exception = new \Exception;
        try {
            $container = new Container();
            $container->set('test', function () use ($exception) {
                throw $exception;
            });
            $res = $container->get('test');
        } catch (ContainerException $e) {
            $this->assertSame($exception, $e->getPrevious());
            return;
        }
        $this->fail();
    }

    public function testGetWithException2()
    {
        $exception = new \Exception;
        try {
            $container = new Container();
            $container->set('test', function () use ($exception) {
                throw $exception;
            });
            $res = $container->test;
        } catch (ContainerException $e) {
            $this->assertSame($exception, $e->getPrevious());
            return;
        }
        $this->fail();
    }

    public function testGetWithException3()
    {
        $exception = new \Exception;
        try {
            $container = new Container();
            $container->set('test', function () use ($exception) {
                throw $exception;
            });
            $res = $container['test'];
        } catch (ContainerException $e) {
            $this->assertSame($exception, $e->getPrevious());
            return;
        }
        $this->fail();
    }

    public function testSetGet()
    {
        $container = new Container();

        $this->assertFalse($container->has('test'));

        $container->set('test', function () {return 42;});

        $this->assertTrue($container->has('test'));
        $this->assertTrue(isset($container->test));
        $this->assertTrue(isset($container['test']));

        $this->assertSame(42, $container->get('test'));
        $this->assertSame(42, $container->test);
        $this->assertSame(42, $container['test']);

        $container->test = function () {return 24;};

        $this->assertSame(24, $container->get('test'));
        $this->assertSame(24, $container->test);
        $this->assertSame(24, $container['test']);

        $container['test'] = function () {return 12;};

        $this->assertSame(12, $container->get('test'));
        $this->assertSame(12, $container->test);
        $this->assertSame(12, $container['test']);
    }

    public function testSingleton()
    {
        $container = new Container();

        $this->assertFalse($container->has('test'));

        $container->singleton('test', function () {return new Std(['foo' => 'bar']);});

        $this->assertTrue($container->has('test'));

        $obj1 = $container->get('test');
        $this->assertEquals(new Std(['foo' => 'bar']), $obj1);

        $obj2 = $container->get('test');
        $this->assertEquals(new Std(['foo' => 'bar']), $obj2);

        $this->assertSame($obj1, $obj2);
    }

}
