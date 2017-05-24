<?php

namespace Runn\tests\Reflection\ReflectionHelpers;

use Runn\Core\Exceptions;
use Runn\Core\Std;
use Runn\Reflection\Exception;
use Runn\Reflection\ReflectionHelpers;

class ReflectionHelpersTest extends \PHPUnit_Framework_TestCase
{

    public function testGetClassMethodArgs()
    {
        $object = new class {
            public function foo() {}
        };

        $args = ReflectionHelpers::getClassMethodArgs(get_class($object), 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(0, $args);

        $object = new class {
            public function foo($arg1) {}
        };

        $args = ReflectionHelpers::getClassMethodArgs(get_class($object), 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(1, $args);
        $this->assertFalse($args['arg1']['optional']);
        $this->assertFalse($args['arg1']['variadic']);
        $this->assertFalse(isset($args['arg1']['default']));

        $object = new class {
            public function foo($arg1 = 'test') {}
        };

        $args = ReflectionHelpers::getClassMethodArgs(get_class($object), 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(1, $args);
        $this->assertTrue($args['arg1']['optional']);
        $this->assertFalse($args['arg1']['variadic']);
        $this->assertTrue(isset($args['arg1']['default']));
        $this->assertSame('test', $args['arg1']['default']);

        $object = new class {
            public function foo(...$args) {}
        };

        $args = ReflectionHelpers::getClassMethodArgs(get_class($object), 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(1, $args);
        $this->assertTrue($args['args']['optional']);
        $this->assertTrue($args['args']['variadic']);
        $this->assertFalse(isset($args['args']['default']));
    }

    /**
     * @expectedException \Runn\Reflection\Exception
     */
    public function testGetObjectMethodInvalidObject()
    {
        $args = ReflectionHelpers::getObjectMethodArgs('', 'foo');
    }

    public function testGetObjectMethodArgs()
    {
        $object = new class {
            public function foo() {}
        };

        $args = ReflectionHelpers::getObjectMethodArgs($object, 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(0, $args);

        $object = new class {
            public function foo($arg1) {}
        };

        $args = ReflectionHelpers::getObjectMethodArgs($object, 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(1, $args);
        $this->assertFalse($args['arg1']['optional']);
        $this->assertFalse($args['arg1']['variadic']);
        $this->assertFalse(isset($args['arg1']['default']));

        $object = new class {
            public function foo($arg1 = 'test') {}
        };

        $args = ReflectionHelpers::getObjectMethodArgs($object, 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(1, $args);
        $this->assertTrue($args['arg1']['optional']);
        $this->assertFalse($args['arg1']['variadic']);
        $this->assertTrue(isset($args['arg1']['default']));
        $this->assertSame('test', $args['arg1']['default']);

        $object = new class {
            public function foo(...$args) {}
        };

        $args = ReflectionHelpers::getObjectMethodArgs($object, 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(1, $args);
        $this->assertTrue($args['args']['optional']);
        $this->assertTrue($args['args']['variadic']);
        $this->assertFalse(isset($args['args']['default']));
    }

    public function testPrepareArgs()
    {
        $args = [];
        $data = [1, 2, 3];
        $this->assertSame([], ReflectionHelpers::prepareArgs($args, $data));

        $args = ['foo' => [], 'bar' => []];
        $data = ['foo' => 42, 'bar' => null, 'baz' => 'some'];
        $this->assertSame(['foo' => 42, 'bar' => null], ReflectionHelpers::prepareArgs($args, $data));

        $args = ['foo' => [], 'bar' => []];
        $data = new Std(['foo' => 42, 'bar' => null, 'baz' => 'some']);
        $this->assertSame(['foo' => 42, 'bar' => null], ReflectionHelpers::prepareArgs($args, $data));

        $args = ['foo' => [], 'bar' => ['optional' => true, 'default' => 'test']];
        $data = ['foo' => 42, 'baz' => 'some'];
        $this->assertSame(['foo' => 42, 'bar' => 'test'], ReflectionHelpers::prepareArgs($args, $data));

        try {
            $args = ['foo' => [], 'bar' => []];
            $data = ['foo' => 42, 'baz' => 'some'];
            ReflectionHelpers::prepareArgs($args, $data);
        } catch (Exceptions $errors) {
            $this->assertCount(1, $errors);
            $this->assertInstanceOf(Exception::class, $errors[0]);
            $this->assertSame('Argument "bar" has not set or default value', $errors[0]->getMessage());
            return;
        }
        $this->fail();
    }

}