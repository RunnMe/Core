<?php

namespace Runn\tests\Core\Config;

use Runn\Core\Config;
use Runn\Storages\SingleValueStorageInterface;

class SimpleFileStorage implements SingleValueStorageInterface {

    protected $file;
    protected $data;

    public function __construct($file) {
        $this->file = $file;
    }

    public function load() {
        $this->data = unserialize(file_get_contents($this->file));
    }

    public function save() {
        file_put_contents($this->file, serialize($this->data));
    }

    public function get()
    {
        return $this->data;
    }

    public function set($value)
    {
        $this->data = $value;
    }
}

class ConfigSaveTest extends \PHPUnit_Framework_TestCase
{

    const TMP_PATH = __DIR__ . '/tmp';

    protected function setUp()
    {
        mkdir(self::TMP_PATH);
        file_put_contents(self::TMP_PATH . '/savetest.config', serialize(['application' => ['name' => 'Test Application']]));
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Empty config storage
     */
    public function testEmptyStorageSave()
    {
        $config = new Config();
        $config->foo = 'bar';
        $config->save();
        $this->fail();
    }

    public function testSave()
    {
        $config = new Config(new SimpleFileStorage(self::TMP_PATH . '/savetest.config'));
        $this->assertEquals('Test Application', $config->application->name);

        $config->foo = 'bar';
        $config->baz = [1, 2, 3];
        $config->songs = ['Hey' => 'Jude', 'I just' => ['call' => ['to' => 'say']]];

        $config->save();

        $expectedText = serialize(['application' => ['name' => 'Test Application'], 'foo' => 'bar', 'baz' => [1, 2, 3], 'songs' => ['Hey' => 'Jude', 'I just' => ['call' => ['to' => 'say']]]]);
        $this->assertEquals(
            str_replace("\r\n", "\n", $expectedText),
            str_replace("\r\n", "\n", file_get_contents(self::TMP_PATH . '/savetest.config'))
        );
    }

    protected function tearDown()
    {
        unlink(self::TMP_PATH . '/savetest.config');
        rmdir(self::TMP_PATH);
    }

}