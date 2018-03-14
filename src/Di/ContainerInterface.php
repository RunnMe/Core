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
     *
     * @throws \Psr\Container\ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return $this
     */
    public function set($id, callable $resolver);

}
