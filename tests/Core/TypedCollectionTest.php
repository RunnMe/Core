<?php

namespace Runn\tests\Core\TypedCollection;

use PHPUnit\Framework\TestCase;
use Runn\Core\Exception;
use Runn\Core\TypedCollection;

class TypedCollectionTest extends TestCase
{

    public function testConstructValid()
    {
        $collection = new class([(object)['foo' => 1], (object)['bar' => 2]])
            extends TypedCollection {
                public static function getType() { return \stdClass::class;}
            };

        $this->assertInstanceOf(TypedCollection::class, $collection);
        $this->assertSame(\stdClass::class, $collection->getType());
        $this->assertCount(2, $collection);

        $this->assertEquals((object)['foo' => 1], $collection[0]);
        $this->assertEquals((object)['bar' => 2], $collection[1]);
    }

    public function testGetType()
    {
        $collection = new class(['type' => (object)['value' => 42]])
            extends TypedCollection {
            public static function getType() { return \stdClass::class;}
        };

        $this->assertSame(\stdClass::class, $collection->getType());
        $this->assertSame(42, $collection['type']->value);
    }

    public function testConstructTypeMismatch()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Typed collection type mismatch');
        $collection = new class(['foo' => 1, 'bar' => 2])
            extends TypedCollection {
                public static function getType() { return \stdClass::class;}
            };
    }

}
