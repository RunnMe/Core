<?php

namespace Runn\tests\Di\ContainerEntryNotFoundException;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Runn\Di\ContainerEntryNotFoundException;
use Runn\Di\ContainerException;

class ContainerEntryNotFoundExceptionTest extends TestCase
{

    public function testEmptyConstruct()
    {
        $this->expectException(\ArgumentCountError::class);
        new ContainerEntryNotFoundException;
    }

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
