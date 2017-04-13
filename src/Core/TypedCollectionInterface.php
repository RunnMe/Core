<?php

namespace Runn\Core;

/**
 * Interface for typed collections
 *
 * Interface TypedCollectionInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface TypedCollectionInterface
    extends CollectionInterface
{

    public static function getType();

}