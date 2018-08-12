<?php

namespace Runn\tests\Di\ContainerException;

use Psr\Container\ContainerExceptionInterface;
use Runn\Di\ContainerException;

class ContainerExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $exception = new ContainerException;
        $this->assertInstanceOf(ContainerException::class, $exception);
        $this->assertInstanceOf(ContainerExceptionInterface::class, $exception);
        $this->assertEmpty($exception->getMessage());
        $this->assertEmpty($exception->getCode());
        $this->assertEmpty($exception->getPrevious());

        $prev = new \Exception;
        $exception = new ContainerException($prev);
        $this->assertInstanceOf(ContainerException::class, $exception);
        $this->assertInstanceOf(ContainerExceptionInterface::class, $exception);
        $this->assertEmpty($exception->getMessage());
        $this->assertEmpty($exception->getCode());
        $this->assertSame($prev, $exception->getPrevious());
    }

}
