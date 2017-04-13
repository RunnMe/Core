<?php

namespace Runn\Storages;

/**
 * Interface StorageAwareInterface
 * @package Runn\Storages
 */
interface StorageAwareInterface
{

    public function setStorage(/*?*/StorageInterface $storage);

    public function getStorage(): /*?*/StorageInterface;

}