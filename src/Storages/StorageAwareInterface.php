<?php

namespace Runn\Storages;

/**
 * Interface StorageAwareInterface
 * @package Runn\Storages
 */
interface StorageAwareInterface
{

    /**
     * @param \Runn\Storages\StorageInterface $storage
     * @return $this
     *
     * @7.1
     */
    public function setStorage(/*?*/StorageInterface $storage);

    /**
     * @return \Runn\Storages\StorageInterface
     *
     * @7.1
     */
    public function getStorage(): /*?*/StorageInterface;

}