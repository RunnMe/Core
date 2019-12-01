<?php

namespace Runn\tests\Reflection\ReflectionHelpers;

use PHPUnit\Framework\TestCase;
use Runn\Core\Exceptions;
use Runn\Core\Std;
use Runn\Reflection\Exception;
use Runn\Reflection\ReflectionHelpers;

class ReflectionHelpersTest extends TestCase
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
            public function foo(string $arg1, int $arg2, array $arg3, $arg4) {}
        };

        $args = ReflectionHelpers::getClassMethodArgs(get_class($object), 'foo');
        $this->assertIsArray($args);
        $this->assertCount(4, $args);
        $this->assertSame('string', $args['arg1']['type']);
        $this->assertSame('int', $args['arg2']['type']);
        $this->assertSame('array', $args['arg3']['type']);
        $this->assertFalse(isset($args['arg4']['type']));

        $object = new class {
            public function foo(...$args) {}
        };

        $args = ReflectionHelpers::getClassMethodArgs(get_class($object), 'foo');
        $this->assertTrue(is_array($args));
        $this->assertCount(1, $args);
        $this->assertTrue($args['args']['optional']);
        $this->assertTrue($args['args']['variadic']);
        $this->assertFalse(isset($args['args']['default']));
        $this->assertFalse(isset($args['args']['type']));

        $reflector = new \ReflectionMethod(ReflectionHelpers::class, 'getClassMethodArgs');
        $staticVariables = $reflector->getStaticVariables();
        $this->assertIsArray($staticVariables);
        $this->assertCount(1, $staticVariables);
        $this->assertNotNull($staticVariables['cache']);

        $object2 = new ReflectionHelpers();
        ReflectionHelpers::getClassMethodArgs(get_class($object2), 'getClassMethodArgs');
        $reflector = new \ReflectionMethod(ReflectionHelpers::class, 'getClassMethodArgs');
        $staticVariables = $reflector->getStaticVariables();
        $this->assertIsArray($staticVariables['cache']);
        $this->assertNotNull($staticVariables['cache'][get_class($object)]['foo']);
        $this->assertNotNull($staticVariables['cache'][get_class($object2)]['getClassMethodArgs']);
    }

    public function testGetObjectMethodInvalidObject()
    {
        $this->expectException(Exception::class);
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
