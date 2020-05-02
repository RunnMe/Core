<?php

namespace Runn\Di;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\StdGetSetInterface;

/**
 * Extended PSR Container interface
 *
 * Interface ContainerInterface
 * @package Runn\Di
 */
interface ContainerInterface extends PsrContainerInterface, ObjectAsArrayInterface, StdGetSetInterface
{

    /**
     * Sets a resolver for entry of the container by its identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @param callable $resolver Function that resolves the entry and returns it.
     *
     * @return $this
     */
    public function set(string $id, callable $resolver);

    /**
     * Sets a resolver for entry of the container by its identifier as singleton.
     *
     * @param string $id Identifier of the entry to look for.
     * @param callable $resolver Function that resolves the entry and returns it.
     *
     * @return $this.
     */
    public function singleton(string $id, callable $resolver);

    /**
     * Resolves and returns an entry with all ones dependencies
     * If $id is class name returns the $id class instance
     *
     * @param string $id
     * @return mixed
     */
    public function resolve(string $id);

}
