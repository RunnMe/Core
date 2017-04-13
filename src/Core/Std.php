<?php

namespace Runn\Core;

/**
 * Standard class
 *
 * Class Std
 * @package Runn\Core
 */
class Std
    implements ObjectAsArrayInterface, StdGetSetInterface, HasInnerValidationInterface, HasInnerSanitizationInterface, HasRequiredInterface
{

    use StdGetSetValidateSanitizeTrait {
        innerGet as protected traitInnerGet;
    }

    /**
     * @var array
     */
    protected static $required = [];

    /**
     * @return array
     */
    public static function getRequiredKeys(): array
    {
        return static::$required;
    }

    protected function innerGet($key)
    {
        if ('requiredKeys' === $key) {
            return $this->__data[$key];
        }
        return $this->traitInnerGet($key);
    }

    /**
     * Checks if all required properties are set
     * @return bool
     * @throws \Runn\Core\Exceptions
     */
    protected function checkRequired()
    {
        $errors = new Exceptions();
        foreach ($this->getRequiredKeys() as $required) {
            if (!isset($this->$required)) {
                $errors->add(new Exception('Required property "' . $required . '" is missing'));
            }
        }
        if (!$errors->isEmpty()) {
            throw $errors;
        }
        return true;
    }

    /**
     * Std constructor.
     * @param iterable|null $data
     * @throws \Runn\Core\Exceptions
     */
    public function __construct(iterable $data = null)
    {
        $errors = new Exceptions();

        if (null !== $data) {
            try {
                $this->fromArray($data);
            } catch (Exceptions $errors) {}
        }

        try {
            $this->checkRequired();
        } catch (\Throwable $e) {
            $errors->add($e);
        }

        if (!$errors->isEmpty()) {
            throw $errors;
        }
    }

}