<?php

namespace Runn\Core;

/**
 * Base Exception class
 *
 * Class Exception
 * @package Runn\Core
 *
 */
class Exception
    extends \Exception
    implements \JsonSerializable
{

    public function jsonSerialize()
    {
        return ['code' => $this->getCode(), 'message' => $this->getMessage()];
    }

}