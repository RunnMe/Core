<?php

namespace Runn\Storages;

/**
 * Interface KeyValueStorageAwareInterface
 * @package Runn\Storages
 */
interface KeyValueStorageAwareInterface
{

    public function setStorage(/*?*/KeyValueStorageInterface $storage);
    public function getStorage(): /*?*/KeyValueStorageInterface;

}