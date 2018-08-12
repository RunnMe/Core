<?php

namespace Runn\tests\Core\ConfigAwareTrait;

use Runn\Core\Config;
use Runn\Core\ConfigAwareInterface;
use Runn\Core\ConfigAwareTrait;

class ConfigAwareTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testTrait()
    {
        $obj = new class implements ConfigAwareInterface{ use ConfigAwareTrait; };
        // @7.1
        $this->assertNull($obj->getConfig());

        $config = new Config(['foo' => 'bar']);

        $ret = $obj->setConfig($config);
        $this->assertSame($config, $obj->getConfig());
        $this->assertSame($obj, $ret);

        // @7.1
        $ret = $obj->setConfig(null);
        $this->assertNull($obj->getConfig());
        $this->assertSame($obj, $ret);
    }

}