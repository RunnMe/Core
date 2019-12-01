<?php

namespace Runn\tests\Core\ConfigAwareTrait;

use PHPUnit\Framework\TestCase;
use Runn\Core\Config;
use Runn\Core\ConfigAwareInterface;
use Runn\Core\ConfigAwareTrait;

class ConfigAwareTraitTest extends TestCase
{

    public function testTrait()
    {
        $obj = new class implements ConfigAwareInterface{ use ConfigAwareTrait; };

        $this->assertNull($obj->getConfig());

        $config = new Config(['foo' => 'bar']);

        $ret = $obj->setConfig($config);
        $this->assertSame($config, $obj->getConfig());
        $this->assertSame($obj, $ret);

        $ret = $obj->setConfig(null);
        $this->assertNull($obj->getConfig());
        $this->assertSame($obj, $ret);
    }

}
