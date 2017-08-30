<?php

namespace Runn\Core;

/**
 * Typed collection class
 *
 * Class TypedCollection
 * @package Runn\Core
 */
abstract class TypedCollection
    implements TypedCollectionInterface
{
    use TypedCollectionTrait;

    /**
     * @param iterable|null $data
     *
     * @7.1
     */
    public function __construct(iterable $data = null)
    {
        if (null !== $data) {
            $this->fromArray($data);
        }
    }

}