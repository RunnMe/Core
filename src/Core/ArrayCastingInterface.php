<?php

namespace Runn\Core;

/**
 * Interface for objects which can be casted from array and be casted to array
 *
 * Interface ArrayCastingInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface ArrayCastingInterface
{

    public function fromArray(/* iterable */$data);

    public function merge(/* iterable */$data);

    public function toArray(): array;

    public function toArrayRecursive(): array;

}