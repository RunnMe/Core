<?php

namespace Runn\Storages;

/**
 * Interface KeyValueStorageAwareInterface
 * @package Runn\Storages
 */
interface KeyValueStorageAwareInterface
{

    /**
     * @param \Runn\Storages\KeyValueStorageInterface|null $storage
     * @return $this
     */
    public function setStorage(?KeyValueStorageInterface $storage);

    /**
     * @return \Runn\Storages\KeyValueStorageInterface|null
     */
    public function getStorage(): ?KeyValueStorageInterface;

}
