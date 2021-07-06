<?php

namespace Runn\Core;

/**
 * Simplest SingletonInterface implementation
 *
 * Trait SingletonTrait
 * @package Runn\Core
 *
 * @implements \Runn\Core\SingletonInterface
 */
trait SingletonTrait
    //implements SingletonInterface
{

    /**
     * @codeCoverageIgnore
     */
    protected function __construct()
    {
    }

    /**
     * @codeCoverageIgnore
     */
    protected function __clone()
    {
    }

    /**
     * @param array $args
     * @return static
     */
    public static function instance(...$args)
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static(...$args);
        }
        return $instance;
    }

}
