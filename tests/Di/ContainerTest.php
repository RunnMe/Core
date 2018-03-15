<?php

namespace Runn\tests\Di\Container;

use Runn\Di\Container;
use Runn\Di\ContainerEntryNotFoundException;
use Runn\Di\ContainerException;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \TypeError
     */
    public function testSetInvalidArgument()
    {
        $container = new Container();
        $container->set('id', 'foo');
    }

    public function testGetInvalidId()
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

    public function testGetWithException()
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

    public function testSetGet()
    {
        $container = new Container();

        $this->assertFalse($container->has('test'));

        $container->set('test', function () {return 42;});

        $this->assertTrue($container->has('test'));
        $this->assertSame(42, $container->get('test'));

    }

}
