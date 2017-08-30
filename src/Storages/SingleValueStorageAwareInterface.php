<?php

namespace Runn\Storages;

/**
 * Interface SingleValueStorageAwareInterface
 * @package Runn\Storages
 */
interface SingleValueStorageAwareInterface
{

    /**
     * @param \Runn\Storages\SingleValueStorageInterface $storage
     * @return $this
     *
     * @7.1
     */
    public function setStorage(?SingleValueStorageInterface $storage);

    /**
     * @return \Runn\Storages\SingleValueStorageInterface
     *
     * @7.1
     */
    public function getStorage(): ?SingleValueStorageInterface;

}