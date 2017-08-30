<?php

namespace Runn\Core;

use Runn\Reflection\ReflectionHelpers;

/**
 * Basic trait for classes that have data schema
 *
 * Trait HasSchemaTrait
 * @package Core
 *
 * @implements \Runn\Core\HasSchemaInterface
 */
trait HasSchemaTrait
    //implements HasSchemaInterface
{

    /*protected static $schema;*/

    /**
     * @return iterable
     *
     * @7.1
     */
    public static function getSchema(): iterable
    {
        return static::$schema;
    }

    /**
     * @param iterable|null $schema
     * @return array
     *
     * @7.1
     */
    protected function prepareDataBySchema(iterable $schema = null)
    {
        $data = [];
        if (null !== $schema) {
            foreach ($schema as $key => $def) {
                $value = $this->prepareValueBySchemaDef($key, $def);
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * @param string $key
     * @param mixed $def
     * @return mixed
     */
    protected function prepareValueBySchemaDef($key, $def)
    {
        if (!empty($def['class'])) {

            $class = $def['class'];
            unset($def['class']);

            // check if $def has only digital keys
            if (ctype_digit(implode('', array_keys($def)))) {
                return new $class(...array_values($def));

            // or not - it has string keys?
            } else {
                $ctor = ReflectionHelpers::getClassMethodArgs($class, '__construct');
                $args = ReflectionHelpers::prepareArgs($ctor, $def);
                return new $class(...array_values($args));
            }
        } else {
            return $def;
        }
    }

    /**
     * @param iterable $schema
     * @return $this
     *
     * @7.1
     */
    public function fromSchema(iterable $schema = null)
    {
        $data = $this->prepareDataBySchema($schema);
        $this->fromArray($data);
        return $this;
    }

}