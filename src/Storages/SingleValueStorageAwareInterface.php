<?php

namespace Runn\Storages;

/**
 * Interface SingleValueStorageAwareInterface
 * @package Runn\Storages
 */
interface SingleValueStorageAwareInterface
{

    /**
     * @param \Runn\Storages\SingleValueStorageInterface|null $storage
     * @return $this
     */
    public function setStorage(?SingleValueStorageInterface $storage);

    /**
     * @return \Runn\Storages\SingleValueStorageInterface|null
     */
    public function getStorage(): ?SingleValueStorageInterface;

}
