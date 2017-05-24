<?php

namespace Runn\Core;

/**
 * InstanceableInterface simplest implementation
 *
 * Trait InstanceableTrait
 * @package Runn\Core
 *
 * @implements \Runn\Core\InstanceableInterface
 */
trait InstanceableTrait
    //implements InstanceableInterface
{

    /**
     * @param array $args
     * @return static
     */
    public static function instance(...$args)
    {
        return new static(...$args);
    }

}