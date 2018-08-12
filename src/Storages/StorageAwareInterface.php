<?php

namespace Runn\Storages;

/**
 * Interface StorageAwareInterface
 * @package Runn\Storages
 */
interface StorageAwareInterface
{

    /**
     * @param \Runn\Storages\StorageInterface|null $storage
     * @return $this
     */
    public function setStorage(?StorageInterface $storage);

    /**
     * @return \Runn\Storages\StorageInterface|null
     */
    public function getStorage(): ?StorageInterface;

}
