<?php

namespace Runn\tests\Core\Collection;

use Runn\Core\Collection;
use Runn\Core\CollectionInterface;

class CollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $collection = new Collection;

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertCount(0, $collection);

        $collection = new Collection([100, 200, 300, [400, 500]]);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertCount(4, $collection);
        
        $this->assertEquals(
            [100, 200, 300, new Collection([400, 500])],
            $collection->values()
        );
        $this->assertEquals(
            [100, 200, 300, [400, 500]],
            $collection->toArray()
        );
        $this->assertEquals(100, $collection[0]);
        $this->assertEquals(200, $collection[1]);
        $this->assertEquals(300, $collection[2]);
        $this->assertEquals(new Collection([400, 500]), $collection[3]);
    }

    public function testConstructWithZeroKey()
    {
        $collection = new Collection([1 => '1', 0 => '0', 2 => '2']);
        $this->assertCount(3, $collection);
        $this->assertSame([1 => '1', 0 => '0', 2 => '2'], $collection->values());
    }

}