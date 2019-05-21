<?php

namespace Runn\tests\Core\Config;

use PHPUnit\Framework\TestCase;
use Runn\Core\Config;
use Runn\Core\Exception;
use Runn\Core\StdGetSetInterface;
use Runn\Core\HasInnerValidationInterface;
use Runn\Core\HasInnerSanitizationInterface;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\Std;
use Runn\Storages\SingleValueStorageInterface;

class FakeStorage implements SingleValueStorageInterface
{

    protected $data;

    public function load() {
        $this->data = ['foo' => 42, 'bar' => 'bla-bla', 'baz' => [1, 2, 3]];
    }

    public function save() {}

    public function get()
    {
        return $this->data;
    }

    public function set($value)
    {
        $this->data = $value;
    }
}

class ConfigTest extends TestCase
{

    public function testConstructWData()
    {
        $obj = new Config(['foo' => 42, 'bar' => 'bla-bla', 'baz' => [1, 2, 3]]);

        $this->assertInstanceOf(ObjectAsArrayInterface::class, $obj);
        $this->assertInstanceOf(StdGetSetInterface::class, $obj);
        $this->assertInstanceOf(HasInnerValidationInterface::class, $obj);
        $this->assertInstanceOf(HasInnerSanitizationInterface::class, $obj);
        $this->assertInstanceOf(Std::class, $obj);
        $this->assertInstanceOf(SingleValueStorageInterface::class, $obj);
        $this->assertInstanceOf(Config::class, $obj);

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    public function testConstructWFile()
    {
        $obj = new Config(new FakeStorage());

        $this->assertInstanceOf(Config::class, $obj);
        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    public function testSetGetStorage()
    {
        $obj = new Config();

        $this->assertNull($obj->getStorage());

        $file = new FakeStorage();
        $ret = $obj->setStorage($file);

        $this->assertSame($obj, $ret);
        $this->assertEquals($file, $obj->getStorage());

        $ret = $obj->setStorage(null);

        $this->assertSame($obj, $ret);
        $this->assertNull($obj->getStorage());
    }

    public function testMagicStorage()
    {
        $obj = new Config();

        $this->assertNull($obj->storage);
        $this->assertNull($obj->getStorage());

        $obj->storage = 'test.txt';

        $this->assertEquals('test.txt', $obj->storage);
        $this->assertNull($obj->getStorage());

        $obj->setStorage(new FakeStorage());

        $this->assertEquals('test.txt', $obj->storage);
        $this->assertEquals(new FakeStorage(), $obj->getStorage());
    }

    public function testLoadEmptyFile()
    {
        $obj = new Config();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Empty config storage');
        $obj->load();
    }

    public function testLoad()
    {
        $file = new FakeStorage();
        $file->load();

        $obj = new Config();
        $obj->setStorage(new FakeStorage())->load();

        $this->assertEquals($file, $obj->getStorage());

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);

        $obj->foo = null;
        unset($obj->bar);
        $obj->baz = 'nothing';

        $obj->load();

        $this->assertCount(3, $obj);
        $this->assertEquals(42, $obj->foo);
        $this->assertEquals('bla-bla', $obj->bar);
        $this->assertEquals(new Config([1, 2, 3]), $obj->baz);
    }

    public function testSet()
    {
        $config = new Config();

        $this->expectException(\BadMethodCallException::class);
        $config->set(42);
    }

    public function testGet()
    {
        $config = new Config(new FakeStorage());

        $this->expectException(\BadMethodCallException::class);
        $data = $config->get();
    }

}
