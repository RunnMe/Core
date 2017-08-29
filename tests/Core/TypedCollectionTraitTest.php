<?php

namespace Runn\tests\Core\TypedCollectionTrait;

use Runn\Core\Exception;
use Runn\Core\TypedCollectionInterface;
use Runn\Core\TypedCollectionTrait;

class testClass
    implements TypedCollectionInterface
{
    use TypedCollectionTrait;
    public static function getType()
    {
        return testValueClass::class;
    }
}

class testStrictClass
    implements TypedCollectionInterface
{
    use TypedCollectionTrait;
    public static function getType()
    {
        return testValueClass::class;
    }
    protected function checkValueType($value)
    {
        if (!$this->isValueTypeValid($value, true)) {
            throw new Exception('Typed collection type mismatch');
        }
    }
}

class testFloatClass
    implements TypedCollectionInterface
{
    use TypedCollectionTrait;
    public static function getType()
    {
        return 'float';
    }
}

class testIntClass
    implements TypedCollectionInterface
{
    use TypedCollectionTrait;
    public static function getType()
    {
        return 'int';
    }
}

class testBoolClass
    implements TypedCollectionInterface
{
    use TypedCollectionTrait;
    public static function getType()
    {
        return 'bool';
    }
}

class testIncorrectTypeClass
    implements TypedCollectionInterface
{
    use TypedCollectionTrait;
    public static function getType()
    {
        return 'foo';
    }
}

class testValueClass
{
    protected $data;
    public function __construct($x)
    {
        $this->data = $x;
    }
    public function getValue()
    {
        return $this->data;
    }
}


class TypedCollectionTraitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidFromArray()
    {
        (new testClass)->fromArray([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidAppend()
    {
        $collection = new testClass();
        $collection->append(42);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidPrepend()
    {
        $collection = new testClass();
        $collection->prepend(new class {});
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidInnerSet()
    {
        $collection = new testClass();
        $collection[] = new class {};
    }

    public function testValid()
    {
        $this->assertSame(testValueClass::class, testClass::getType());

        $collection = (new testClass)->fromArray([new testValueClass(1), new testValueClass(2)]);

        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertCount(2, $collection);

        $collection->append(new testValueClass(3));
        $this->assertCount(3, $collection);

        $collection->prepend(new testValueClass(4));
        $this->assertCount(4, $collection);

        $collection[] = new testValueClass(5);
        $this->assertCount(5, $collection);
    }

    public function testValidStrictType()
    {
        $this->assertSame(testValueClass::class, testStrictClass::getType());
        $collection = (new testStrictClass)->fromArray([new testValueClass(1), new testValueClass(2)]);

        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertCount(2, $collection);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidStrictType()
    {
        $this->assertSame(testValueClass::class, testStrictClass::getType());
        $collection = (new testStrictClass)->fromArray([new class (1) extends testValueClass {}]);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidIntegerClass()
    {
        $collection = new testClass();
        $collection->append('42');
    }

    public function testValidIntegerClass()
    {
        $collection = (new testIntClass)->fromArray([1, 2]);

        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertCount(2, $collection);

        $collection->append(3);
        $this->assertCount(3, $collection);

        $collection->prepend(4);
        $this->assertCount(4, $collection);

        $collection[] = 5;
        $this->assertCount(5, $collection);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidFloatClass()
    {
        $collection = new testClass();
        $collection->append(true);
    }

    public function testValidFloatClass()
    {
        $collection = (new testFloatClass)->fromArray([1.0, 2.1]);

        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertCount(2, $collection);

        $collection->append(3.2);
        $this->assertCount(3, $collection);

        $collection->prepend(4.3);
        $this->assertCount(4, $collection);

        $collection[] = 5.4;
        $this->assertCount(5, $collection);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalidBooleanClass()
    {
        $collection = new testClass();
        $collection->append('42');
    }

    public function testValidBooleanClass()
    {
        $collection = (new testBoolClass)->fromArray([true, true]);

        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertCount(2, $collection);

        $collection->append(false);
        $this->assertCount(3, $collection);

        $collection->prepend(true);
        $this->assertCount(4, $collection);

        $collection[] = false;
        $this->assertCount(5, $collection);
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testIncorrectTypeClass()
    {
        $collection = new testIncorrectTypeClass();
        $collection->append(42);
    }

}