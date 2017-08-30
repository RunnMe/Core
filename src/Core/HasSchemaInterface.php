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
     * @return iterable
     *
     * @7.1
     */
    public static function getSchema(): iterable;

    /**
     * @param iterable|null $schema
     * @return $this
     *
     * @7.1
     */
    public function fromSchema(iterable $schema = null);

}