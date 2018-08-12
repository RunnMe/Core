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

    use StdGetSetValidateSanitizeTrait;

    /**
     * @return array
     */
    protected function notgetters(): array
    {
        return ['requiredKeys'];
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
        if (!$errors->empty()) {
            throw $errors;
        }
        return true;
    }

    /**
     * Std constructor.
     * @param iterable|null $data
     * @throws \Runn\Core\Exceptions
     *
     * @7.1
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

        if (!$errors->empty()) {
            throw $errors;
        }
    }

}