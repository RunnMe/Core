<?php

namespace Runn\tests\Core\HasSchemaTrait;

use Runn\Core\HasSchemaInterface;
use Runn\Core\HasSchemaTrait;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\ObjectAsArrayTrait;
use Runn\Core\Std;

class testClass
    implements ObjectAsArrayInterface, HasSchemaInterface
{
    protected static $schema = [
        ['class' => Std::class, 'foo' => 'bar', 'baz' => 42],
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

}