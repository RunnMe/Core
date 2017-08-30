<?php

namespace Runn\Core;

/**
 * MultiException (Exceptions Collection) base class
 *
 * Class Exceptions
 * @package Runn\Core
 */
class Exceptions
    extends \Exception
    implements TypedCollectionInterface
{

    public static function getType()
    {
        return \Throwable::class;
    }

    use TypedCollectionTrait {
        add as protected collectionAdd;
    }

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

    /**
     * @param $value
     * @return $this
     */
    public function add($value)
    {
        if ($value instanceof self) {
            foreach ($value as $v) {
                $this->collectionAdd($v);
            }
        } else {
            $this->collectionAdd($value);
        }
        return $this;
    }

}