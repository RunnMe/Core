<?php

namespace Runn\Core;

/**
 * Trait CollectionTrait
 * @package Runn\Core
 *
 * @implements \Runn\Core\ObjectAsArrayInterface
 *
 * @implements \Runn\Core\CollectionInterface
 */
trait CollectionTrait
    // implements CollectionInterface
{

    use ObjectAsArrayTrait;

    /**
     * Does value need cast to this (or another) class?
     * @param mixed $value
     * @return bool
     */
    protected function needCasting($key, $value): bool
    {
        if (is_null($value) || is_scalar($value) || $value instanceof \Closure || $value instanceof ObjectAsArrayInterface || is_object($value)) {
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
    public function merge(iterable $data)
    {
        if ($data instanceof ArrayCastingInterface) {
            $data = $data->toArray();
        }

        foreach ($data as $key => $value) {
            if ($this->needCasting($key, $value)) {
                $value = $this->innerCast($key, $value);
            }
            if (is_int($key) && $this->innerIsSet($key)) {
                $this->innerSet(null, $value);
            } else {
                $this->innerSet($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function add($value)
    {
        return $this->append($value);
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function append($value)
    {
        if ($this->needCasting(null, $value)) {
            $value = $this->innerCast(null, $value);
        }
        $this->__data = array_merge($this->__data, [$value]);
        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function prepend($value)
    {
        if ($this->needCasting(null, $value)) {
            $value = $this->innerCast(null, $value);
        }
        $this->__data = array_merge([$value], $this->__data);
        return $this;
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return mixed
     */
    public function slice(int $offset, int $length = null)
    {
        return (new static)->fromArray(array_slice($this->__data, $offset, $length));
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->slice(0, 1)[0];
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return $this->slice(-1, 1)[0];
    }

    /**
     * @param iterable $attributes
     * @return bool
     *
     * @7.1
     */
    public function existsElementByAttributes(iterable $attributes): bool
    {
        if (empty($attributes)) {
            return false;
        }
        foreach ($this as $element) {
            $elementAttributes = [];
            if (!is_array($element) && !(is_object($element) && $element instanceof \Traversable)) {
                continue;
            }
            foreach ($element as $key => $val) {
                foreach ($attributes as $attrkey => $attribute) {
                    if ($attrkey == $key) {
                        $elementAttributes[$key] = $val;
                    }
                }
            }
            if ($attributes == $elementAttributes)
                return true;
        }
        return false;
    }

    /**
     * @param iterable $attributes
     * @return static
     *
     * @7.1
     */
    public function findAllByAttributes(iterable $attributes)
    {
        return $this->filter(function ($x) use ($attributes) {
            if (!is_array($x) && !(is_object($x) && $x instanceof \Traversable)) {
                return false;
            }
            $elementAttributes = [];
            foreach ($x as $key => $value) {
                foreach ($attributes as $attrkey => $attribute) {
                    if ($attrkey == $key) {
                        $elementAttributes[$key] = $value;
                    }
                }
            }
            return $elementAttributes == $attributes;
        });
    }

    /**
     * @param iterable $attributes
     * @return mixed|null
     *
     * @7.1
     */
    public function findByAttributes(iterable $attributes)
    {
        $all = $this->findAllByAttributes($attributes);
        return $all->empty() ? null : $all[0];
    }

    /**
     * @return static
     */
    public function asort()
    {
        $copy = $this->toArray();
        asort($copy);
        return (new static)->fromArray($copy);
    }

    /**
     * @return static
     */
    public function ksort()
    {
        $copy = $this->toArray();
        ksort($copy);
        return (new static)->fromArray($copy);
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function uasort(callable $callback) {
        $copy = $this->toArray();
        uasort($copy, $callback);
        return (new static)->fromArray($copy);
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function uksort(callable $callback) {
        $copy = $this->toArray();
        uksort($copy, $callback);
        return (new static)->fromArray($copy);
    }

    /**
     * @return static
     */
    public function natsort() {
        $copy = $this->toArray();
        natsort($copy);
        return (new static)->fromArray($copy);
    }

    /**
     * @return static
     */
    public function natcasesort() {
        $copy = $this->toArray();
        natcasesort($copy);
        return (new static)->fromArray($copy);
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function sort(callable $callback)
    {
        return $this->uasort($callback);
    }

    /**
     * @return static
     */
    public function reverse() {
        $clone = clone $this;
        $clone->__data = array_reverse($clone->__data, true);
        return $clone;
    }


    /**
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        return (new static)->fromArray(array_values(array_map($callback, $this->toArray())));
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback)
    {
        return (new static)->fromArray(array_values(array_filter($this->toArray(), $callback)));
    }

    /**
     * @param mixed $start
     * @param callable $callback
     * @return mixed
     */
    public function reduce($start, callable $callback)
    {
        return array_reduce($this->toArray(), $callback, $start);
    }

    /**
     * @param mixed $what
     * @return array
     */
    public function collect($what)
    {
        $ret = [];
        foreach ($this as $element) {
            if ($what instanceof \Closure) {
                $ret[] = $what($element);
            } elseif (is_array($element) || ($element instanceof ObjectAsArrayInterface)) {
                $ret[] = $element[$what];
            } elseif (is_object($element)) {
                $ret[] = $element->$what;
            }
        }
        return $ret;
    }

    /**
     * @param $by
     * @return array|static[]
     */
    public function group($by)
    {
        $ret = [];
        foreach ($this as $element) {
            if ($by instanceof \Closure) {
                $key = $by($element);
            } elseif (is_array($element) || ($element instanceof ObjectAsArrayInterface)) {
                $key = $element[$by];
            } elseif (is_object($element)) {
                $key = $element->$by;
            }
            if (!isset($ret[$key])) {
                $ret[$key] = new static;
            }
            $ret[$key]->add($element);
        }
        return $ret;
    }

    public function __call(string $method, array $params = [])
    {
        foreach ($this as $element) {
            call_user_func_array([$element, $method], $params);
        }
    }

}