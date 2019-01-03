<?php

namespace Runn\Core;

/**
 * Own "DateTime" class
 *
 * Class DateTime
 * @package Runn\Core
 */
class DateTime extends \DateTime implements \JsonSerializable
{

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->format('c');
    }

}
