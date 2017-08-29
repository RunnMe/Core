<?php

namespace Runn\tests\Core\StdGetSetValidateSanitizeTrait;

use Runn\Core\Exception;
use Runn\Core\StdGetSetInterface;
use Runn\Core\HasInnerValidationInterface;
use Runn\Core\HasInnerSanitizationInterface;
use Runn\Core\Exceptions;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\StdGetSetValidateSanitizeTrait;

class testClass
    implements ObjectAsArrayInterface, StdGetSetInterface, HasInnerValidationInterface, HasInnerSanitizationInterface
{
    use StdGetSetValidateSanitizeTrait;

    protected function notsetters(): array { return ['bla1']; }

    protected function validateFoo()
    {
        throw new Exception('Invalid foo');
    }
    protected function validateBar()
    {
        throw new Exceptions([
            new Exception('Invalid bar 1'),
            new Exception('Invalid bar 2')
        ]);
    }
    protected function validateBaz()
    {
        yield new Exception('Invalid baz 1');
        yield new Exception('Invalid baz 2');
    }

    public function setBla1($val) {
        $this->__data['bla1'] = '!!!';
    }
    public function setBla2($val) {
        $this->__data['bla2'] = '!!!';
    }
}

class StdGetSetValidateSanitizeTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testNullKey()
    {
        $obj = new testClass();

        $obj[] = '1';
        $this->assertCount(1, $obj);
        $this->assertSame('1', $obj[0]);

        $obj[2] = '2';
        $this->assertCount(2, $obj);
        $this->assertSame('1', $obj[0]);
        $this->assertSame('2', $obj[2]);
    }

    public function testNotSetters()
    {
        $obj = new testClass();

        $obj->bla1 = 'test1';
        $this->assertSame('test1', $obj->bla1);

        $obj->bla2 = 'test2';
        $this->assertSame('!!!', $obj->bla2);
    }

    public function testSimpleExceptionFromArray()
    {
        try {
            $obj = new testClass();
            $obj->fromArray(['foo' => 1]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(0, $obj);
            $this->assertCount(1, $errors);
            $this->assertSame('Invalid foo', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testSimpleExceptionMerge()
    {
        try {
            $obj = (new testClass())->fromArray(['sample' => 'test']);
            $obj->merge(['foo' => 1]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(1, $obj);
            $this->assertSame('test', $obj->sample);

            $this->assertCount(1, $errors);
            $this->assertSame('Invalid foo', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testMultiExceptionFromArray()
    {
        try {
            $obj = new testClass();
            $obj->fromArray(['bar' => 1]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(0, $obj);
            $this->assertCount(2, $errors);
            $this->assertSame('Invalid bar 1', $errors[0]->getMessage());
            $this->assertSame('Invalid bar 2', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testMultiExceptionMerge()
    {
        try {
            $obj = (new testClass())->fromArray(['sample' => 'test']);
            $obj->merge(['bar' => 1]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(1, $obj);
            $this->assertSame('test', $obj->sample);

            $this->assertCount(2, $errors);
            $this->assertSame('Invalid bar 1', $errors[0]->getMessage());
            $this->assertSame('Invalid bar 2', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testGenerateExceptionFromArray()
    {
        try {
            $obj = new testClass();
            $obj->fromArray(['baz' => 1]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(0, $obj);
            $this->assertCount(2, $errors);
            $this->assertSame('Invalid baz 1', $errors[0]->getMessage());
            $this->assertSame('Invalid baz 2', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testGenerateExceptionMerge()
    {
        try {
            $obj = (new testClass())->fromArray(['sample' => 'test']);
            $obj->merge(['baz' => 1]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(1, $obj);
            $this->assertSame('test', $obj->sample);

            $this->assertCount(2, $errors);
            $this->assertSame('Invalid baz 1', $errors[0]->getMessage());
            $this->assertSame('Invalid baz 2', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testComplexFromArray()
    {
        try {
            $obj = new testClass();
            $obj->fromArray(['foo' => 1, 'bar' =>2, 'baz' => 3]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(0, $obj);
            $this->assertCount(5, $errors);
            $this->assertSame('Invalid foo', $errors[0]->getMessage());
            $this->assertSame('Invalid bar 1', $errors[1]->getMessage());
            $this->assertSame('Invalid bar 2', $errors[2]->getMessage());
            $this->assertSame('Invalid baz 1', $errors[3]->getMessage());
            $this->assertSame('Invalid baz 2', $errors[4]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testComplexMerge()
    {
        try {
            $obj = (new testClass())->fromArray(['sample' => 'test']);
            $obj->merge(['foo' => 1, 'bar' =>2, 'baz' => 3]);
            $this->fail();
        } catch (Exceptions $errors) {

            $this->assertCount(1, $obj);
            $this->assertSame('test', $obj->sample);

            $this->assertCount(5, $errors);
            $this->assertSame('Invalid foo', $errors[0]->getMessage());
            $this->assertSame('Invalid bar 1', $errors[1]->getMessage());
            $this->assertSame('Invalid bar 2', $errors[2]->getMessage());
            $this->assertSame('Invalid baz 1', $errors[3]->getMessage());
            $this->assertSame('Invalid baz 2', $errors[4]->getMessage());

            return;
        }
        $this->fail();
    }

}