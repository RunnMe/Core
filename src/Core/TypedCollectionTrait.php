<?php

namespace Runn\Core;

/**
 * Class TypedCollectionTrait
 * @package Runn\Core
 *
 * @implements \Runn\Core\TypedCollectionInterface
 */
trait TypedCollectionTrait
    /*implements TypedCollectionInterface*/
{

    use CollectionTrait {
        append as protected collectionAppend;
        prepend as protected collectionPrepend;
        innerSet as protected collectionInnerSet;
    }

    /**
     * @return array
     */
    protected function notgetters(): array
    {
        return ['type'];
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    protected function isValueTypeValid($value, $strict = false): bool
    {
        $type = static::getType();

        if (class_exists($type) || interface_exists($type)) {
            return is_object($value) && ( $strict ? ($type == get_class($value)) : ($value instanceof $type) );
        }

        switch (gettype($value)) {
            case 'boolean':
                if ('bool' == $type || 'boolean' == $type) {
                    return true;
                }
            default:
                $typeChecker = 'is_' . $type;
                if (function_exists($typeChecker)) {
                    return $typeChecker($value);
                }
        }
        return false;
    }

    protected function checkValueType($value)
    {
        if (!$this->isValueTypeValid($value)) {
            throw new Exception('Typed collection type mismatch');
        }
    }

    public function append($value)
    {
        $this->checkValueType($value);
        return $this->collectionAppend($value);
    }

    public function prepend($value)
    {
        $this->checkValueType($value);
        return $this->collectionPrepend($value);
    }

    public function innerSet($key, $value)
    {
        $this->checkValueType($value);
        $this->collectionInnerSet($key, $value);
    }
}
