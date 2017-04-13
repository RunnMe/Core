<?php

namespace Runn\tests\Core\Std;

use Runn\Core\Exception;
use Runn\Core\HasRequiredInterface;
use Runn\Core\StdGetSetInterface;
use Runn\Core\HasInnerValidationInterface;
use Runn\Core\HasInnerSanitizationInterface;
use Runn\Core\Exceptions;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\Std;

class testClass extends Std {
    protected function validateFoo($val) {
        return $val>0;
    }
    protected function sanitizeBar($val) {
        return trim($val);
    }
    protected function setBaz($val) {
        $this->__data['baz'] = $val*2;
    }
}

class testClassWExceptions extends Std {
    protected function validateFoo($val) {
        if ($val < 0) {
            throw new Exception('Minus');
        }
        return true;
    }
    protected function validateBar($val) {
        if (strlen($val) < 3) {
            yield new Exception('Small');
        }
        if (false !== strpos($val, '0')) {
            yield new Exception('Zero');
        }
        return true;
    }
    protected function validateBaz($val) {
        if ($val > 100) {
            throw new Exception('Large');
        }
        return true;
    }
}

class testClassWithRequired extends Std {
    protected static $required = ['foo', 'bar'];
    protected function validateBaz() {
        throw new Exception('Invalid baz');
    }
}

class StdTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyConstruct()
    {
        $obj = new Std;

        $this->assertInstanceOf(ObjectAsArrayInterface::class, $obj);
        $this->assertInstanceOf(StdGetSetInterface::class, $obj);
        $this->assertInstanceOf(HasInnerValidationInterface::class, $obj);
        $this->assertInstanceOf(HasInnerSanitizationInterface::class, $obj);
        $this->assertInstanceOf(HasRequiredInterface::class, $obj);

        $this->assertCount(0, $obj);
    }

    public function testValidConstruct()
    {
        $obj = new Std(['foo' => 42, 'bar' => 'bla-bla', 'baz' => [1, 2, 3]]);

        $this->assertInstanceOf(ObjectAsArrayInterface::class, $obj);
        $this->assertInstanceOf(StdGetSetInterface::class, $obj);
        $this->assertInstanceOf(HasInnerValidationInterface::class, $obj);
        $this->assertInstanceOf(HasInnerSanitizationInterface::class, $obj);
        $this->assertInstanceOf(HasRequiredInterface::class, $obj);

        $this->assertCount(3, $obj);

        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Std([1, 2, 3]), $obj->baz);
    }

    public function testGetRequiredKeys()
    {
        $obj = new testClassWithRequired(['foo' => 1, 'bar' => 2]);
        $obj->requiredKeys = 42;

        $this->assertSame(['foo', 'bar'], $obj->getRequiredKeys());
        $this->assertSame(42, $obj->requiredKeys);
    }

    public function testMerge()
    {
        $obj1 = new Std(['foo' => 1]);
        $obj1->merge(['bar' => 2]);
        $this->assertEquals(1, $obj1->foo);
        $this->assertEquals(2, $obj1->bar);
        $this->assertEquals(new Std(['foo' => 1, 'bar' => 2]), $obj1);

        $obj2 = new Std(['foo' => 11]);
        $obj2->merge(new Std(['bar' => 21]));
        $this->assertEquals(11, $obj2->foo);
        $this->assertEquals(21, $obj2->bar);
        $this->assertEquals(new Std(['foo' => 11, 'bar' => 21]), $obj2);

        $obj2 = new Std(['foo' => 11, 'bar' => 12]);
        $obj2->merge(new Std(['bar' => 21]));
        $this->assertEquals(11, $obj2->foo);
        $this->assertEquals(21, $obj2->bar);
        $this->assertEquals(new Std(['foo' => 11, 'bar' => 21]), $obj2);
    }

    public function testSetter()
    {
        $obj = new testClass();
        $obj->baz = 42;

        $this->assertTrue(isset($obj->baz));
        $this->assertEquals(84, $obj->baz);
    }

    public function testValidate()
    {
        $obj = new testClass();
        $obj->foo = 42;

        $this->assertTrue(isset($obj->foo));
        $this->assertEquals(42, $obj->foo);

        $obj = new testClass();
        $obj->foo = -42;

        $this->assertFalse(isset($obj->foo));
    }

    public function testSanitize()
    {
        $obj = new testClass();
        $obj->bar = '  test    ';

        $this->assertTrue(isset($obj->bar));
        $this->assertEquals('test', $obj->bar);
    }

    public function testRequiredMissing()
    {
        try {
            $obj = new testClassWithRequired();
            $this->fail();
        } catch (Exceptions $errors) {
            $this->assertCount(2, $errors);
            $this->assertInstanceOf(Exception::class, $errors[0]);
            $this->assertInstanceOf(Exception::class, $errors[1]);
            $this->assertEquals('Required property "foo" is missing', $errors[0]->getMessage());
            $this->assertEquals('Required property "bar" is missing', $errors[1]->getMessage());
            return;
        }
    }

    public function testRequiredMissingAndInvalid()
    {
        try {
            $obj = new testClassWithRequired(['baz' => 1]);
            $this->fail();
        } catch (Exceptions $errors) {
            $this->assertCount(3, $errors);
            $this->assertInstanceOf(Exception::class, $errors[0]);
            $this->assertInstanceOf(Exception::class, $errors[1]);
            $this->assertInstanceOf(Exception::class, $errors[2]);
            $this->assertEquals('Invalid baz', $errors[0]->getMessage());
            $this->assertEquals('Required property "foo" is missing', $errors[1]->getMessage());
            $this->assertEquals('Required property "bar" is missing', $errors[2]->getMessage());
            return;
        }
    }

    public function testRequiredValid()
    {
        try {
            $obj = new testClassWithRequired(['foo' => 1, 'bar' => 2]);
            $this->assertCount(2, $obj);
        } catch (Exceptions $errors) {
            $this->fail();
        }
    }

}