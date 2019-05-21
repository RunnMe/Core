<?php

namespace Runn\tests\Core\Exception;

use PHPUnit\Framework\TestCase;
use Runn\Core\Exception;

class ExceptionTest extends TestCase
{

    public function testJson()
    {
        $e = new Exception();
        $this->assertSame('{"code":0,"message":""}', json_encode($e));

        $e = new Exception('Some error');
        $this->assertSame('{"code":0,"message":"Some error"}', json_encode($e));

        $e = new Exception('Some error', 42);
        $this->assertSame('{"code":42,"message":"Some error"}', json_encode($e));
    }

}
