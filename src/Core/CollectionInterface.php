<?php

namespace Runn\Core;

/**
 * Object-as-collection interface
 *
 * Interface CollectionInterface
 * @package Runn\Core
 *
 * @codeCoverageIgnore
 */
interface CollectionInterface
    extends ObjectAsArrayInterface
{

    public function add($value);
    public function prepend($value);
    public function append($value);

    public function slice(int $offset, int $length = null);
    public function first();
    public function last();

    /**
     * @param array $attributes
     * @return bool
     *
     * @7.1
     */
    public function existsElementByAttributes(array /* iterable */ $attributes): bool;

    /**
     * @param array $attributes
     * @return static
     *
     * @7.1
     */
    public function findAllByAttributes(array /* iterable */ $attributes);

    /**
     * @param array $attributes
     * @return mixed|null
     *
     * @7.1
     */
    public function findByAttributes(array /* iterable */ $attributes);

    public function asort();
    public function ksort();
    public function uasort(callable $callback);
    public function uksort(callable $callback);
    public function natsort();
    public function natcasesort();
    public function sort(callable $callback);
    public function reverse();

    public function map(callable $callback);
    public function filter(callable $callback);
    public function reduce($start, callable $callback);

    public function collect($what);
    public function group($by);

    public function __call(string $method, array $params = []);

}