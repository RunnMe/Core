<?php

namespace Runn\Storages;

/**
 * Object that stores some single value, can save this value into external storage and load saved value from one
 *
 * Interface SingleValueStorageInterface
 * @package Runn\Storages
 */
interface SingleValueStorageInterface
    extends StorageInterface
{

    /**
     * Load value from external storage into this object
     * @return mixed
     */
    public function load();

    /**
     * Save value from this object into external storage
     */
    public function save();

    /**
     * Returns stored in this object value
     * @return mixed
     */
    public function get();

    /**
     * Stores the value into this object
     * @param mixed $value
     */
    public function set($value);

}