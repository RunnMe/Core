<?php

namespace Runn\tests\Core\Exceptions;

use Runn\Core\Exception;
use Runn\Core\CollectionInterface;
use Runn\Core\Exceptions;

class SomeException extends Exception
{
}

class ExceptionsTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $errors = new Exceptions();
        $this->assertInstanceOf(
            Exceptions::class,
            $errors
        );
        $this->assertInstanceOf(
            CollectionInterface::class,
            $errors
        );
        $this->assertTrue($errors->empty());
    }

    public function testConstructValid()
    {
        $errors = new Exceptions([new Exception('First'), new Exception('Second')]);

        $this->assertEquals(2, $errors->count());
        $this->assertEquals(
            [new Exception('First'), new Exception('Second')],
            $errors->toArray()
        );
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testConstructInvalid()
    {
        $errors = new Exceptions([new \stdClass('First'), new \stdClass('Second')]);
    }

    public function testAppend()
    {
        $errors = new Exceptions;
        $this->assertTrue($errors->empty());

        $errors->append(new Exception('First'));
        $this->assertFalse($errors->empty());
        $this->assertEquals(1, $errors->count());

        $errors->append(new Exception('Second'));
        $this->assertFalse($errors->empty());
        $this->assertEquals(2, $errors->count());

        $this->assertEquals(
            [new Exception('First'), new Exception('Second')],
            $errors->toArray()
        );
    }

    public function testPrepend()
    {
        $errors = new Exceptions;
        $this->assertTrue($errors->empty());

        $errors->prepend(new Exception('First'));
        $this->assertFalse($errors->empty());
        $this->assertEquals(1, $errors->count());

        $errors->prepend(new Exception('Second'));
        $this->assertFalse($errors->empty());
        $this->assertEquals(2, $errors->count());

        $this->assertEquals(
            [new Exception('Second'), new Exception('First')],
            $errors->toArray()
        );
    }

    public function testAdd()
    {
        $errors = new Exceptions;
        $this->assertTrue($errors->empty());

        $errors->add(new Exception('First'));
        $this->assertFalse($errors->empty());
        $this->assertEquals(1, $errors->count());

        $errors->add(new Exception('Second'));
        $this->assertFalse($errors->empty());
        $this->assertEquals(2, $errors->count());

        $this->assertInstanceOf(
            \Runn\Core\Exception::class,
            $errors[0]
        );
        $this->assertInstanceOf(
            \Runn\Core\Exception::class,
            $errors[1]
        );
        $this->assertEquals(new Exception('First'), $errors[0]);
        $this->assertEquals(new Exception('Second'), $errors[1]);
    }

    public function testAddSelf()
    {
        $errors = new Exceptions;
        $this->assertTrue($errors->empty());

        $errors->add(new Exception('First'));
        $this->assertFalse($errors->empty());
        $this->assertEquals(1, $errors->count());

        $merged = new Exceptions;
        $merged[] = new Exception('Second');
        $merged[] = new Exception('Third');
        $this->assertEquals(2, $merged->count());

        $errors->add($merged);
        $this->assertEquals(3, $errors->count());
        $this->assertEquals(new Exception('First'), $errors[0]);
        $this->assertEquals(new Exception('Second'), $errors[1]);
        $this->assertEquals(new Exception('Third'), $errors[2]);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidClassPrepend()
    {
        $errors = new class extends Exceptions
        {
            public static function getType()
            {
                return SomeException::class;
            }
        };
        $errors->prepend(new Exception);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidClassAppend()
    {
        $errors = new class extends Exceptions
        {
            public static function getType()
            {
                return SomeException::class;
            }
        };
        $errors->append(new Exception);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidInnserSet()
    {
        $errors = new class extends Exceptions
        {
            public static function getType()
            {
                return SomeException::class;
            }
        };
        $errors[] = new Exception;
    }

    public function testThrow()
    {
        try {

            $errors = new Exceptions();
            $errors->add(new Exception('Foo'));
            $errors->add(new Exception('Bar'));
            $errors->add(new Exception('Baz'));

            if (!$errors->empty()) {
                throw $errors;
            }

            $this->fail();

        } catch (Exceptions $ex) {

            $this->assertEquals(3, $ex->count());

            $this->assertInstanceOf(
                \Runn\Core\Exception::class,
                $ex[0]
            );
            $this->assertInstanceOf(
                \Runn\Core\Exception::class,
                $ex[1]
            );
            $this->assertInstanceOf(
                \Runn\Core\Exception::class,
                $ex[2]
            );

            $this->assertEquals('Foo', $ex[0]->getMessage());
            $this->assertEquals('Bar', $ex[1]->getMessage());
            $this->assertEquals('Baz', $ex[2]->getMessage());

        }
    }

}