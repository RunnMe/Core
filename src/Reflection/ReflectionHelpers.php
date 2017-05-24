<?php

namespace Runn\Reflection;
use Runn\Core\Exceptions;

/**
 * Some useful helpers for reflection classes and methods
 *
 * Class ReflectionHelpers
 * @package Runn\Reflection
 */
class ReflectionHelpers
{

    /**
     * Returns the argument list by object and its method name
     *
     * @param string $class
     * @param string $method
     * @return array
     * @throws \Runn\Reflection\Exception
     */
    public static function getClassMethodArgs($class, string $method)
    {
        $reflector = new \ReflectionMethod($class, $method);
        $params = $reflector->getParameters();
        if (empty($params)) {
            return [];
        }
        $args = [];
        foreach ($params as $param) {
            $args[$param->name] = [
                'optional' => $param->isOptional(),
                'variadic' => $param->isVariadic(),
            ];
            if ($param->isOptional() && $param->isDefaultValueAvailable()) {
                $args[$param->name]['default'] = $param->getDefaultValue();
            }
        }
        return $args;
    }

    /**
     * Returns the argument list by object and its method name
     *
     * @param object $obj
     * @param string $method
     * @return array
     * @throws \Runn\Reflection\Exception
     */
    public static function getObjectMethodArgs($obj, string $method)
    {
        if (!is_object($obj)) {
            throw new Exception('$obj is not an object');
        }
        return static::getClassMethodArgs(get_class($obj), $method);
    }

    /**
     * @param array $args
     * @param array|\Runn\Core\ObjectAsArrayInterface $data
     * @return array
     * @throws \Runn\Core\Exceptions
     */
    public static function prepareArgs(array $args, $data)
    {
        $ret = [];
        $errors = new Exceptions;

        foreach ($args as $name => $def) {
            if ((is_array($data) && array_key_exists($name, $data)) || isset($data[$name])) {
                $ret[$name] = $data[$name];
                continue;
            }
            if (array_key_exists('optional', $def) && true === $def['optional'] && array_key_exists('default', $def)) {
                $ret[$name] = $def['default'];
                continue;
            }
            $errors[] = new Exception('Argument "' . $name . '" has not set or default value');
        }
        if (!$errors->empty()) {
            throw $errors;
        }
        return $ret;
    }

}