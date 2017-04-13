<?php

namespace Runn\Storages;

/**
 * Object that stores data, can save custom values into external storage by keys and load saved values from one
 *
 * Interface KeyValueStorageInterface
 * @package Runn\Storages
 */
interface KeyValueStorageInterface
    extends StorageInterface
{

    /**
     * Load data from external storage into this object by specified key or all data by all keys (if $key is null)
     * @param int|string|null $key
     */
    public function load($key = null);

    /**
     * Save data from this object into external storage by specified key or all data by all keys (if $key is null)
     * @param int|string|null $key
     * @return mixed
     */
    public function save($key = null);

    /**
     * Returns stored in this object value by specified key
     * @param int|string|null $key
     * @return mixed
     */
    public function get($key);

    /**
     * Stores the value by specified key in this object
     * @param int|string|null $key
     * @param mixed $value
     */
    public function set($key, $value);

}