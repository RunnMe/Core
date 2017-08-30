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
     *
     * @7.1
     */
    public function setStorage(?KeyValueStorageInterface $storage);

    /**
     * @return \Runn\Storages\KeyValueStorageInterface|null
     *
     * @7.1
     */
    public function getStorage(): ?KeyValueStorageInterface;

}