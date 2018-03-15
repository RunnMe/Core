<?php

namespace Runn\tests\Di\ContainerException;

use Psr\Container\ContainerExceptionInterface;
use Runn\Di\ContainerEntryNotFoundException;
use Runn\Di\ContainerException;

class ContainerEntryNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @7.1
     * expectedException \ArgumentCountError
     */
    /*
    public function testEmptyConstruct()
    {
        $exception = new ContainerEntryNotFoundException;
    }
    */

    public function testConstruct()
    {
        $exception = new ContainerEntryNotFoundException('foo');
        $this->assertInstanceOf(ContainerEntryNotFoundException::class, $exception);
        $this->assertInstanceOf(ContainerException::class, $exception);
        $this->assertInstanceOf(ContainerExceptionInterface::class, $exception);
        $this->assertSame('foo', $exception->getId());
        $this->assertEmpty($exception->getMessage());
        $this->assertEmpty($exception->getCode());
        $this->assertEmpty($exception->getPrevious());

        $prev = new \Exception;
        $exception = new ContainerEntryNotFoundException('foo', $prev);
        $this->assertInstanceOf(ContainerException::class, $exception);
        $this->assertInstanceOf(ContainerExceptionInterface::class, $exception);
        $this->assertSame('foo', $exception->getId());
        $this->assertEmpty($exception->getMessage());
        $this->assertEmpty($exception->getCode());
        $this->assertSame($prev, $exception->getPrevious());
    }

}
