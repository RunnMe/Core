<?php

namespace Runn\Storages;

/**
 * Interface SingleValueStorageAwareInterface
 * @package Runn\Storages
 */
interface SingleValueStorageAwareInterface
{

    public function setStorage(/*?*/SingleValueStorageInterface $storage);
    public function getStorage(): /*?*/SingleValueStorageInterface;

}