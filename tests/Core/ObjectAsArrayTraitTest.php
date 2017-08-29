<?php

namespace Runn\tests\Core\ObjectAsArrayTrait;

use Runn\Core\ArrayCastingInterface;
use Runn\Core\HasInnerCastingInterface;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\ObjectAsArrayTrait;

class testClass
    implements ObjectAsArrayInterface
{
    use ObjectAsArrayTrait;
}

class testAnotherClass
    implements ObjectAsArrayInterface
{
    use ObjectAsArrayTrait;
}

class testWithGetterClass
    implements ObjectAsArrayInterface
{
    use ObjectAsArrayTrait;
    protected function getFoo()
    {
        return 42;
    }
}

class testWithGetterNotgetterClass
    implements ObjectAsArrayInterface
{
    use ObjectAsArrayTrait;
    protected function notgetters(): array { return['bar']; }
    protected function getFoo()
    {
        return 42;
    }
    protected function getBar()
    {
        return 'baz';
    }
}

class testWithSetterClass
    implements ObjectAsArrayInterface
{
    use ObjectAsArrayTrait;
    protected function setFoo($val)
    {
        $this->__data['foo'] = $val*2;
    }
}

class testWithSetterNotsetterClass
    implements ObjectAsArrayInterface
{
    use ObjectAsArrayTrait;
    protected function notsetters(): array { return ['bar']; }
    protected function setFoo($val)
    {
        $this->__data['foo'] = $val*2;
    }
    protected function setBar($val)
    {
        $this->__data['bar'] = $val/2;
    }
}

class ObjectAsArrayTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testInterfaces()
    {
        $obj = new testClass();

        $this->assertInstanceOf(ObjectAsArrayInterface::class,   $obj);
        $this->assertInstanceOf(\ArrayAccess::class,             $obj);
        $this->assertInstanceOf(\Countable::class,               $obj);
        $this->assertInstanceOf(\Iterator::class,                $obj);
        $this->assertInstanceOf(ArrayCastingInterface::class,    $obj);
        $this->assertInstanceOf(HasInnerCastingInterface::class, $obj);
        $this->assertInstanceOf(\Serializable::class,            $obj);
        $this->assertInstanceOf(\JsonSerializable::class,        $obj);
    }

    public function testKeysValues()
    {
        $obj = new testClass();
        $obj[1] = 100;
        $obj[2] = '200';
        $obj['foo'] = 'bar';
        $obj[] = 'baz';

        $this->assertSame([1, 2, 'foo', 3], $obj->keys());
        $this->assertSame([1=>100, 2=>'200', 'foo'=>'bar', 3=>'baz'], $obj->values());
    }

    public function testIsEmpty()
    {
        $obj = new testClass();
        $this->assertTrue($obj->empty());

        $obj[0] = 1;
        $this->assertFalse($obj->empty());

        unset($obj[0]);
        $this->assertTrue($obj->empty());
    }

    public function testExistsSame()
    {
        $obj = new testClass();
        $obj[1] = 100;
        $obj[2] = '200';

        $this->assertTrue($obj->existsSame(100));
        $this->assertFalse($obj->existsSame('100'));
        $this->assertTrue($obj->existsSame('200'));
        $this->assertFalse($obj->existsSame(200));
    }

    public function testSearchSame()
    {
        $obj = new testClass();
        $obj[1] = 100;
        $obj[2] = '200';

        $this->assertSame(1, $obj->searchSame(100));
        $this->assertNull($obj->searchSame('100'));
        $this->assertSame(2, $obj->searchSame('200'));
        $this->assertNull($obj->searchSame(200));
        $this->assertNull($obj->searchSame(300));
    }

    public function testArrayAccess()
    {
        $obj = new testClass();
        $obj[1] = 100;
        $obj[2] = '200';
        $obj[] = 300;

        $this->assertTrue(isset($obj[1]));
        $this->assertTrue(isset($obj[2]));
        $this->assertTrue(isset($obj[3]));

        $this->assertEquals(100, $obj[1]);
        $this->assertEquals('200', $obj[2]);
        $this->assertEquals(300, $obj[3]);

        unset($obj[2]);

        $this->assertTrue(isset($obj[1]));
        $this->assertFalse(isset($obj[2]));
        $this->assertTrue(isset($obj[3]));
    }

    public function testCountable()
    {
        $obj = new testClass();

        $this->assertCount(0, $obj);

        $obj[] = 'foo';
        $obj[] = 'bar';

        $this->assertCount(2, $obj);

        unset($obj[0]);

        $this->assertCount(1, $obj);
    }

    public function testIterator()
    {
        $obj = new testClass();
        $obj['foo'] = 100;
        $obj['bar'] = 200;
        $obj[300]   = 'baz';

        $res = '';
        foreach ($obj as $key => $val) {
            $res .= $key . '=' . $val . ';';
        }

        $this->assertEquals('foo=100;bar=200;300=baz;', $res);
    }

    public function testNeedCasting()
    {
        $method = new \ReflectionMethod(testClass::class, 'needCasting');
        $closure = $method->getClosure(new testClass());

        $this->assertFalse($closure(null, null));
        $this->assertFalse($closure(null, 42));
        $this->assertFalse($closure(null, 3.14159));
        $this->assertFalse($closure(null, 'foo'));
        $this->assertFalse($closure(null, function () {return 0;}));
        $this->assertFalse($closure(null, new testClass(['foo' => 'bar'])));
        $this->assertFalse($closure(null, new \stdClass()));

        $this->assertTrue($closure(null, [1, 2, 3]));
    }

    public function testFromArray()
    {
        // First: create data
        $obj = new testClass();
        $obj->fromArray(['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]]);

        $this->assertInstanceOf(testClass::class, $obj);
        $this->assertInstanceOf(testClass::class, $obj['baz']);

        $this->assertCount(3, $obj);

        $this->assertEquals(100, $obj['foo']);
        $this->assertEquals(200, $obj['bar']);

        $this->assertEquals((new testClass())->fromArray(['one' => 1, 'two' => 2]), $obj['baz']);
        $this->assertEquals(1, $obj['baz']['one']);
        $this->assertEquals(2, $obj['baz']['two']);

        // Next: rewrite data
        $obj->fromArray(['sample' => 1, 'test' => 2]);

        $this->assertCount(2, $obj);

        $this->assertEquals(1, $obj['sample']);
        $this->assertEquals(2, $obj['test']);

        $this->assertFalse(isset($obj['foo']));
        $this->assertFalse(isset($obj['bar']));
        $this->assertFalse(isset($obj['baz']));
    }

    public function testMerge()
    {
        // Create data
        $obj = new testClass();
        $obj->fromArray(['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]]);

        // Merge new data now!
        $obj->merge(['sample' => 1, 'test' => 2]);

        $this->assertCount(5, $obj);

        $this->assertEquals(100, $obj['foo']);
        $this->assertEquals(200, $obj['bar']);
        $this->assertEquals((new testClass())->fromArray(['one' => 1, 'two' => 2]), $obj['baz']);
        $this->assertEquals(1, $obj['baz']['one']);
        $this->assertEquals(2, $obj['baz']['two']);
        $this->assertEquals(1, $obj['sample']);
        $this->assertEquals(2, $obj['test']);
    }

    public function testMergeWithArrayable()
    {
        // Create data
        $obj = new testClass();
        $obj->fromArray(['sample' => 100, 'test' => 200]);

        $this->assertCount(2, $obj);
        $this->assertEquals(100, $obj['sample']);
        $this->assertEquals(200, $obj['test']);

        // Create arrayable
        $merged = new testWithGetterClass();
        $merged['foo'] = 300;

        // Merge now
        $obj->merge($merged);

        $this->assertCount(3, $obj);
        $this->assertEquals(100, $obj['sample']);
        $this->assertEquals(200, $obj['test']);
        $this->assertEquals(42, $obj['foo']);
    }

    public function testToArray()
    {
        $obj = new testClass();
        $obj->fromArray(['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]]);
        $arr = $obj->toArray();

        $this->assertTrue(is_array($arr));
        $this->assertEquals(
            ['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]],
            $arr
        );

        $obj = new testClass();
        $obj['foo'] = 100;
        $obj['bar'] = 200;
        $obj['baz'] = (new testAnotherClass())->fromArray(['one' => 1, 'two' => 2]);
        $arr = $obj->toArray();

        $this->assertTrue(is_array($arr));
        $this->assertEquals(
            ['foo' => 100, 'bar' => 200, 'baz' => (new testAnotherClass())->fromArray(['one' => 1, 'two' => 2])],
            $arr
        );
    }

    public function testToArrayRecursive()
    {
        $obj = new testClass();
        $obj['foo'] = 100;
        $obj['bar'] = 200;
        $obj['baz'] = (new testAnotherClass())->fromArray(['one' => 1, 'two' => 2]);
        $arr = $obj->toArrayRecursive();

        $this->assertTrue(is_array($arr));
        $this->assertEquals(
            ['foo' => 100, 'bar' => 200, 'baz' => ['one' => 1, 'two' => 2]],
            $arr
        );
    }

    public function testGetter()
    {
        $obj = new testWithGetterClass();

        $this->assertTrue(isset($obj['foo']));

        $obj[1] = 100;
        $obj['foo'] = 200;

        $this->assertEquals(100, $obj[1]);
        $this->assertEquals(42,  $obj['foo']);
    }

    public function testGetterNotGetter()
    {
        $obj = new testWithGetterNotgetterClass();

        $this->assertTrue(isset($obj['foo']));
        $this->assertFalse(isset($obj['bar']));

        $this->assertSame(42,   $obj['foo']);
        $this->assertSame(null, $obj['bar']);
    }

    public function testSetter()
    {
        $obj = new testWithSetterClass();
        $obj[1] = 100;
        $obj['foo'] = 200;

        $this->assertEquals(100, $obj[1]);
        $this->assertEquals(400,  $obj['foo']);
    }

    public function testSetterNotsetter()
    {
        $obj = new testWithSetterNotsetterClass();
        $obj['foo'] = 200;
        $obj['bar'] = 400;

        $this->assertEquals(400,  $obj['foo']);
        $this->assertEquals(400,  $obj['bar']);
    }

    public function testSerialize()
    {
        $obj = new testClass();
        $obj->fromArray([1=>100, 2=>200, 'foo'=>'bar']);

        $this->assertContains('{a:3:{i:1;i:100;i:2;i:200;s:3:"foo";s:3:"bar";}', serialize($obj));
        $this->assertEquals($obj, unserialize(serialize($obj)));
    }

    public function testJsonSerialize()
    {
        $obj = new testClass;
        $obj->fromArray([1=>100, 2=>200, 'foo'=>'bar']);

        $this->assertEquals('{"1":100,"2":200,"foo":"bar"}', json_encode($obj));

        $obj = new testWithGetterClass;
        $obj->fromArray(['foo' => 100]);

        $this->assertEquals('{"foo":42}', json_encode($obj));
    }
    
    public function testSiblingClasses()
    {
        $obj = new testClass();
        $obj->fromArray(['foo' => new testAnotherClass()]);
        
        $this->assertInstanceOf(testClass::class, $obj);
        $this->assertInstanceOf(testAnotherClass::class, $obj['foo']);
    }

}