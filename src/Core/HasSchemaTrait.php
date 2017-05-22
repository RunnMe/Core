<?php

namespace Runn\Core;

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
     */
    public static function getSchema(): array/*iterable*/
    {
        return static::$schema;
    }

    /**
     * @param iterable $schema
     * @return $this
     */
    public function fromSchema(/*iterable */$schema = [])
    {
        $data = [];
        foreach ($schema as $key => $def)
        {
            if (!empty($def['class'])) {
                $class = $def['class'];
                unset($def['class']);
                $data[$key] = new $class(...array_values($def));
            } else {
                $data[$key] = $def;
            }
        }
        $this->fromArray($data);
        return $this;
    }

}