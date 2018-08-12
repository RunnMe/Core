<?php

namespace Runn\Core;

/**
 * Trait ObjectAsArrayTrait
 * @package Runn\Core
 *
 * @implements \ArrayAccess
 * @implements \Countable
 * @implements \Iterator
 * @implements \Runn\Core\ArrayCastingInterface
 * @implements \Serializable
 * @implements \JsonSerializable
 *
 * @implements \Runn\Core\ObjectAsArrayInterface
 */
trait ObjectAsArrayTrait
    //implements ObjectAsArrayInterface
{

    /** @var array $__data */
    protected $__data = [];

    /*
     * Data access protected methods
     */

    /**
     * @return array
     */
    protected function notgetters(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function notsetters(): array
    {
        return [];
    }

    protected function innerIsSet($key)
    {
        return
            array_key_exists($key, $this->__data)
            ||
            ( !in_array($key, $this->notgetters()) && method_exists($this, 'get' . ucfirst($key)) ) ;
    }

    protected function innerUnSet($key)
    {
        unset($this->__data[$key]);
    }

    protected function innerGet($key)
    {
        $method = 'get' . ucfirst($key);
        if ( !in_array($key, $this->notgetters()) && method_exists($this, $method) ) {
            return $this->$method();
        }
        return isset($this->__data[$key]) ? $this->__data[$key] : null;
    }

    protected function innerSet($key, $val)
    {
        $method = 'set' . ucfirst($key);
        if ( !in_array($key, $this->notsetters()) && method_exists($this, $method) ) {
            $this->$method($val);
        } else {
            if (null === $key) {
                $this->__data[] = $val;
            } else {
                $this->__data[$key] = $val;
            }
        }
    }

    /*
     * ObjectAsArrayInterface implementation
     */

    /**
     * Returns array of all object's keys
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->__data);
    }

    /**
     * Returns array of all object's stored values
     * @return array
     */
    public function values(): array
    {
        $ret = [];
        foreach (array_keys($this->__data) as $key) {
            $ret[$key] = $this->innerGet($key);
        }
        return $ret;
    }

    /**
     * Returns true if this object-as-array ie empty (contains zero elements)
     * Otherwise returns false
     * @return bool
     */
    public function empty(): bool
    {
        return empty($this->__data);
    }

    /**
     * Returns true if the same (===) element exists in this object-as-array
     * Otherwise returns false
     * @param $element
     * @return bool
     */
    public function existsSame($element): bool
    {
        return false !== array_search($element, $this->values(), true);
    }

    /**
     * Returns index of first found same (===) element
     * MUST returns null if the same element is not found
     * @param mixed $element
     * @return int|string|bool|null
     */
    public function searchSame($element)
    {
        $key = array_search($element, $this->values(), true);
        return false === $key ? null : $key;
    }

    /**
     * \ArrayAccess implementation
     */
    public function offsetExists($offset)
    {
        return $this->innerIsSet($offset);
    }

    public function offsetUnset($offset)
    {
        $this->innerUnSet($offset);
    }

    public function offsetGet($offset)
    {
        return $this->innerGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->innerSet($offset, $value);
    }

    /**
     * \Countable implementation
     */
    public function count()
    {
        return count($this->__data);
    }

    /**
     * \Iterator implementation
     */
    public function current()
    {
        return $this->innerGet(key($this->__data));
    }

    public function next()
    {
        next($this->__data);
    }

    public function key()
    {
        return key($this->__data);
    }

    public function valid()
    {
        return null !== key($this->__data);
    }

    public function rewind()
    {
        reset($this->__data);
    }

    /**
     * \Runn\Core\HasInnerCastingInterface and \Runn\Core\ArrayCastingInterface implementation
     */

    /**
     * Does value need cast to this (or another) class?
     * @param mixed $key
     * @param mixed $value
     * @return bool
     */
    protected function needCasting($key, $value): bool
    {
        if (is_null($value) || is_scalar($value) || is_object($value)) {
            return false;
        }
        return true;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function innerCast($key, $value)
    {
        return (new static)->fromArray($value);
    }

    /**
     * @param iterable $data
     * @return $this
     *
     * @7.1
     */
    public function fromArray(iterable $data)
    {
        $this->__data = [];
        $this->merge($data);
        return $this;
    }

    /**
     * @param iterable $data
     * @return $this
     *
     * @7.1
     */
    public function merge(iterable $data)
    {
        if ($data instanceof ArrayCastingInterface) {
            $data = $data->toArray();
        }

        foreach ($data as $key => $value) {
            if ($this->needCasting($key, $value)) {
                $value = $this->innerCast($key, $value);
            }
            $this->innerSet($key, $value);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = [];
        foreach (array_keys($this->__data) as $key) {
            $value = $this->innerGet($key);
            if ($value instanceof self) {
                $data[$key] = $value->toArray();
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    /**
     * @return array
     */
    public function toArrayRecursive() : array
    {
        $data = [];
        foreach (array_keys($this->__data) as $key) {
            $value = $this->innerGet($key);
            if ($value instanceof ArrayCastingInterface) {
                $data[$key] = $value->toArrayRecursive();
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * \Serializable implementation
     */

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->__data);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->__data = unserialize($serialized);
    }

    /**
     * \JsonSerializable implementation
     */

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->values();
    }

}