<?php

namespace Runn\Core;

/**
 * Full object-as-array access interface
 *
 * Interface ObjectAsArrayInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface ObjectAsArrayInterface
    extends \ArrayAccess, \Countable, \Iterator, ArrayCastingInterface, HasInnerCastingInterface, \Serializable, \JsonSerializable
{

    /**
     * Returns array of all object's keys
     * @return array
     */
    public function keys(): array;

    /**
     * Returns array of all object's stored values
     * @return array
     */
    public function values(): array;

    /**
     * Returns true if this object-as-array ie empty (contains zero elements)
     * Otherwise returns false
     * @return bool
     */
    public function empty(): bool;

    /**
     * Returns true if the same (===) element exists in this object-as-array
     * Otherwise returns false
     * @param mixed $element
     * @return bool
     */
    public function existsSame($element): bool;

    /**
     * Returns index of first found same (===) element
     * MUST returns null if the same element is not found
     * @param mixed $element
     * @return int|string|bool|null
     */
    public function searchSame($element);

}