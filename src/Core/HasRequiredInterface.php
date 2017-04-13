<?php

namespace Runn\Core;

/**
 * Blank interface for objects have required properties (keys)
 *
 * Interface HasInnerCastingInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface HasRequiredInterface
{

    public static function getRequiredKeys(): array;

}