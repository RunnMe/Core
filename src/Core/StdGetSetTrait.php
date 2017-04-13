<?php

namespace Runn\Core;

/**
 * Trait StdGetSetTrait
 * @package Runn\Core
 *
 * @implements \ArrayAccess
 * @implements \Countable
 * @implements \Iterator
 * @implements \Runn\Core\ArrayCastingInterface
 * @implements \Runn\Core\HasInnerCastingInterface
 * @implements \Serializable
 * @implements \JsonSerializable
 * @implements \Runn\Core\ObjectAsArrayInterface
 *
 * @implements \Runn\Core\StdGetSetInterface
 */
trait StdGetSetTrait
    // implements StdGetSetInterface
{

    use ObjectAsArrayTrait;

    public function __isset($key)
    {
        return $this->innerIsSet($key);
    }

    public function __unset($key)
    {
        $this->innerUnSet($key);
    }

    public function __get($key)
    {
        if (!$this->innerIsSet($key)) {
            $debug = debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0];
            if ($debug['function'] == '__get' && $debug['object'] === $this && $debug['type'] == '->') {
                $property = $debug['args']['0'];
                $line = (file($debug['file'])[$debug['line'] - 1]);
                if (preg_match('~\-\>' . $property . '\-\>.+\=~', $line, $m)) {
                    $this->__data[$property] = new static;
                    return $this->__data[$property];
                }
            }
        }
        return $this->innerGet($key);
    }

    public function __set($key, $val)
    {
        $this->innerSet($key, $val);
    }

}