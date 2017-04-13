<?php

namespace Runn\Core;

/**
 * Interface SingletonInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface SingletonInterface
{

    public static function instance(...$args);

}