<?php

namespace Runn\tests\Core\StdGetSetTrait;

use Runn\Core\StdGetSetInterface;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\StdGetSetTrait;

class testClass
    implements ObjectAsArrayInterface, StdGetSetInterface
{
    use StdGetSetTrait;
}

class StdGetSetTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSetIssetUnset()
    {
        $obj = new testClass();
        $obj->foo = 42;
        $obj->bar = 'bla-bla';
        $obj->baz = [1, 2, 3];

        $this->assertInstanceOf(StdGetSetInterface::class, $obj);

        $this->assertCount(3, $obj);

        $this->assertTrue(isset($obj->foo));
        $this->assertTrue(isset($obj['foo']));
        $this->assertTrue(isset($obj->bar));
        $this->assertTrue(isset($obj['bar']));
        $this->assertTrue(isset($obj->baz));
        $this->assertTrue(isset($obj['baz']));

        $this->assertEquals(42, $obj->foo);
        $this->assertEquals(42, $obj['foo']);
        $this->assertEquals($obj->foo, $obj['foo']);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals('bla-bla', $obj['bar']);
        $this->assertEquals($obj->bar, $obj['bar']);
        $this->assertEquals([1, 2, 3], $obj->baz);
        $this->assertEquals([1, 2, 3], $obj['baz']);
        $this->assertEquals($obj->baz, $obj['baz']);

        unset($obj->baz);

        $this->assertCount(2, $obj);
        $this->assertFalse(isset($obj->baz));
        $this->assertFalse(isset($obj['baz']));
    }

    public function testChain()
    {
        $obj = new testClass();
        $this->assertFalse(isset($obj->foo));
        $this->assertFalse(isset($obj->foo->bar));
        $this->assertTrue(empty($obj->foo));
        $this->assertTrue(empty($obj->foo->bar));

        $obj->foo->bar = 'baz';
        $this->assertTrue(isset($obj->foo));
        $this->assertTrue(isset($obj->foo->bar));
        $this->assertFalse(empty($obj->foo));
        $this->assertFalse(empty($obj->foo->bar));

        $this->assertTrue($obj->foo instanceof testClass);
        $this->assertEquals((new testClass)->fromArray(['bar' => 'baz']), $obj->foo);
        $this->assertEquals('baz', $obj->foo->bar);

    }

}