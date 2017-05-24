<?php

namespace Runn\tests\Core\InstanceableTrait;

use Runn\Core\InstanceableInterface;
use Runn\Core\InstanceableTrait;

class testClass1
    implements InstanceableInterface
{
    use InstanceableTrait;

    public $x;
    public $y;

    public function __construct($x = null, $y = null)
    {
        $this->x = $x;
        $this->y = $y;
    }
}

class InstanceableTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testWoArguments()
    {
        $obj = testClass1::instance();

        $this->assertInstanceOf(testClass1::class, $obj);
        $this->assertNull($obj->x);
        $this->assertNull($obj->y);
    }


    public function testWArguments()
    {
        $obj = testClass1::instance(1, -1);

        $this->assertInstanceOf(testClass1::class, $obj);
        $this->assertEquals(1, $obj->x);
        $this->assertEquals(-1, $obj->y);
    }

}