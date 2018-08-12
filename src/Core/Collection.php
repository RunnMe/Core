<?php

namespace Runn\Core;

/**
 * Collection class
 *
 * Class Collection
 * @package Runn\Core
 */
class Collection
    implements CollectionInterface
{

    use CollectionTrait;

    /**
     * @param iterable|null $data
     */
    public function __construct(iterable $data = null)
    {
        if (null !== $data) {
            $this->fromArray($data);
        }
    }

}
