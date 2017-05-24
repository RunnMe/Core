<?php

namespace Runn\Core;

/**
 * Interface for objects that can be instanced via Class::instance() method
 *
 * Interface InstanceableInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface InstanceableInterface
{

    /**
     * @return static
     */
    public static function instance();

}