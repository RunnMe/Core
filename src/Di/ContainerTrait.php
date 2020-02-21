<?php

namespace Runn\Di;

use Runn\Core\StdGetSetTrait;

/**
 * Extended Container interface default implementation
 *
 * Trait ContainerTrait
 * @package Runn\Di
 */
trait ContainerTrait /*implements ContainerInterface*/
{

    use StdGetSetTrait;

    protected $singletons = [];
    protected $resolved = [];

    /**
     * Sets a resolver for entry of the container by its identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @param callable $resolver Function that resolves the entry and returns it.
     *
     * @return $this.
     */
    public function set($id, callable $resolver)
    {
        $this->innerSet($id, $resolver);
        unset($this->resolved[$id]);
        unset($this->singletons[$id]);
        return $this;
    }

    /**
     * Sets a resolver for entry of the container by its identifier as singleton.
     *
     * @param string $id Identifier of the entry to look for.
     * @param callable $resolver Function that resolves the entry and returns it.
     *
     * @return $this.
     */
    public function singleton($id, callable $resolver)
    {
        $this->innerSet($id, $resolver);
        unset($this->resolved[$id]);
        $this->singletons[$id] = true;
        return $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws ContainerEntryNotFoundException No entry was found for **this** identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new ContainerEntryNotFoundException($id);
        }
        try {
            if (isset($this->singletons[$id])) {
                if (!isset($this->resolved[$id])) {
                    $this->resolved[$id] = $this->innerGet($id)();
                }
                return $this->resolved[$id];
            }
            return $this->innerGet($id)();
        } catch (\Throwable $e) {
            throw new ContainerException($e);
        }
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this[$id]);
    }

    /**
     * @param $key
     * @param $val
     */
    public function __set($key, $val)
    {
        $this->set($key, $val);
    }

    /**
     * @param $key
     * @return mixed
     * @throws ContainerEntryNotFoundException
     * @throws ContainerException
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return mixed
     * @throws ContainerEntryNotFoundException
     * @throws ContainerException
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

}
