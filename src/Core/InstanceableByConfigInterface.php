<?php

namespace Runn\Core;

/**
 * Interface for objects that can be instanced via Class::instance(Config $config) method
 *
 * Interface InstanceableByConfigInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface InstanceableByConfigInterface
    extends InstanceableInterface
{

    /**
     * @param \Runn\Core\Config|null $config
     * @return static
     */
    public static function instance(Config $config = null);

}