<?php

namespace Runn\Core;

/**
 * Interface for classes have "magic" get- set- methods
 *
 * Interface StdGetSetInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface StdGetSetInterface
{

    public function __isset($key);

    public function __unset($key);

    public function __get($key);

    public function __set($key, $val);

}