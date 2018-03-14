<?php

namespace Runn\Di;

use Runn\Core\StdGetSetInterface;
use Runn\Core\StdGetSetTrait;

class Container implements StdGetSetInterface, ContainerInterface
{

    use StdGetSetTrait;

    /**
     * Sets a resolver for entry of the container by its identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @param callable $resolver Function that resolves the entry and returns it.
     *
     * @throws \Psr\Container\ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return $this.
     */
    public function set($id, callable $resolver)
    {
        $this[$id] = $resolver;
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
            return $this[$id]();
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

}
