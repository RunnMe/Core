<?php

namespace Runn\Di;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{

    /**
     * Sets a resolver for entry of the container by its identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @param callable $resolver Function that resolves the entry and returns it.
     * @param bool $singleton Is entry singleton?
     *
     * @return $this
     */
    public function set($id, callable $resolver, bool $singleton = false);

    /**
     * Sets a resolver for entry of the container by its identifier as singleton.
     *
     * @param string $id Identifier of the entry to look for.
     * @param callable $resolver Function that resolves the entry and returns it.
     *
     * @return $this.
     */
    public function singleton($id, callable $resolver);

}
