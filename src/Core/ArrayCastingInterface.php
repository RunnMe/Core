<?php

namespace Runn\Core;

/**
 * Interface for objects which can be casted from array and be casted to array
 *
 * Interface ArrayCastingInterface
 * @package Runn\Core
 *
 */
interface ArrayCastingInterface
{

    /**
     * @param iterable $data
     * @return $this
     *
     * @7.1
     */
    public function fromArray(iterable $data);

    /**
     * @param iterable $data
     * @return $this
     *
     * @7.1
     */
    public function merge(iterable $data);

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function toArrayRecursive(): array;

}