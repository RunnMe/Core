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
     *
     * @7.1
     */
    public function setStorage(?SingleValueStorageInterface $storage);

    /**
     * @return \Runn\Storages\SingleValueStorageInterface|null
     *
     * @7.1
     */
    public function getStorage(): ?SingleValueStorageInterface;

}