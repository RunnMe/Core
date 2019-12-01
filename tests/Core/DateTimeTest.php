<?php

namespace Runn\tests\Core\DateTime;

use PHPUnit\Framework\TestCase;
use Runn\Core\DateTime;

class DateTimeTest extends TestCase
{

    public function testInstance()
    {
        $time = date('Y-m-d H:i:s', time());
        $datetime = new DateTime($time);

        $this->assertInstanceOf(DateTime::class, $datetime);
        $this->assertInstanceOf(\DateTime::class, $datetime);
        $this->assertInstanceOf(\JsonSerializable::class, $datetime);

        $this->assertEquals(new \DateTime($time), $datetime);
    }

    public function testJson()
    {
        $time = date('Y-m-d H:i:s', time());
        $datetime = new DateTime($time);

        $this->assertSame('"' . date('c', strtotime($time)) . '"', json_encode($datetime));
    }

}
