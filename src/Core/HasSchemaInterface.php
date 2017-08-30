<?php

namespace Runn\Core;

/**
 * Common interface for all classes that have data schema
 *
 * Interface HasSchemaInterface
 * @package Core
 */
interface HasSchemaInterface
    extends ArrayCastingInterface
{

    /**
     * @return array
     *
     * @7.1
     */
    public static function getSchema(): array/*iterable*/;

    /**
     * @param iterable $schema
     * @return $this
     *
     * @7.1
     */
    public function fromSchema(/*iterable */$schema = []);

}