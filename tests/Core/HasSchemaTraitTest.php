<?php

namespace Runn\tests\Core\HasSchemaTrait;

use Runn\Core\HasSchemaInterface;
use Runn\Core\HasSchemaTrait;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\ObjectAsArrayTrait;
use Runn\Core\Std;

class testConstructClass {
    public $foo, $bar;
    public function __construct($foo, $bar = 'bar')
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}

class testClass
    implements ObjectAsArrayInterface, HasSchemaInterface
{
    protected static $schema = [
        'x' => ['class' => Std::class, ['foo' => 'bar', 'baz' => 42]],
        'y' => ['class' => testConstructClass::class, 'bar' => 'test2', 'foo' => 'test1'],
        'z' => ['class' => testConstructClass::class, 'foo' => 'test1'],
        42,
    ];

    use ObjectAsArrayTrait;
    use HasSchemaTrait;
}

class HasSchemaTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testInterfaces()
    {
        $obj = new testClass();

        $this->assertInstanceOf(ObjectAsArrayInterface::class,   $obj);
        $this->assertInstanceOf(HasSchemaInterface::class,       $obj);
    }

    public function testGetSchema()
    {
        $obj = new testClass();
        $reflector = new \ReflectionProperty(testClass::class, 'schema');
        $reflector->setAccessible(true);
        $this->assertSame($reflector->getValue($obj), $obj->getSchema());
    }

    public function testFromSchema()
    {
        $obj = new testClass();
        $schema = $obj->getSchema();

        $obj->fromSchema($schema);

        $this->assertInstanceOf(Std::class, $obj['x']);
        $this->assertSame('bar', $obj['x']->foo);
        $this->assertSame(42, $obj['x']->baz);

        $this->assertInstanceOf(testConstructClass::class, $obj['y']);
        $this->assertSame('test1', $obj['y']->foo);
        $this->assertSame('test2', $obj['y']->bar);

        $this->assertInstanceOf(testConstructClass::class, $obj['z']);
        $this->assertSame('test1', $obj['z']->foo);
        $this->assertSame('bar',   $obj['z']->bar);

        $this->assertSame(42, $obj[0]);

    }

}