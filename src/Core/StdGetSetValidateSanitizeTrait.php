<?php

namespace Runn\Core;

/**
 * Class StdGetSetWValidateSanitizeTrait
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
 * @implements \Runn\Core\StdGetSetInterface
 *
 * @implements \Runn\Core\HasInnerValidationInterface
 * @implements \Runn\Core\HasInnerSanitizationInterface
 */
trait StdGetSetValidateSanitizeTrait
    // implements HasInnerValidationInterface, HasInnerSanitizationInterface
{

    use StdGetSetTrait;

    /**
     * This method is reloaded for on-set validation and sanitizing
     * @param string $key
     * @param mixed $val
     * @throws \Runn\Core\Exceptions
     */
    protected function innerSet($key, $val)
    {
        $setMethod = 'set' . ucfirst($key);
        if ( !in_array($key, $this->notsetters()) && method_exists($this, $setMethod) ) {
            $this->$setMethod($val);
        } else {

            $validateMethod = 'validate' . ucfirst($key);

            if (method_exists($this, $validateMethod)) {

                $errors = new Exceptions();

                try {
                    $validateResult = $this->$validateMethod($val);
                    if (false === $validateResult) {
                        return;
                    }
                    if ($validateResult instanceof \Generator) {
                        foreach ($validateResult as $error) {
                            if ($error instanceof \Throwable) {
                                $errors->add($error);
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    $errors->add($e);
                }

                if (!$errors->empty()) {
                    throw $errors;
                }

            }

            $sanitizeMethod = 'sanitize' . ucfirst($key);
            if (method_exists($this, $sanitizeMethod)) {
                $val = $this->$sanitizeMethod($val);
            }

            if (null === $key) {
                $this->__data[] = $val;
            } else {
                $this->__data[$key] = $val;
            }
        }
    }

    /**
     * @param iterable $data
     * @return $this
     * @throws \Runn\Core\Exceptions
     *
     * @7.1
     */
    public function merge(iterable $data)
    {
        if ($data instanceof ArrayCastingInterface) {
            $data = $data->toArray();
        }

        $errors = new Exceptions();

        foreach ($data as $key => $value) {
            try {
                if ($this->needCasting($key, $value)) {
                    $value = $this->innerCast($key, $value);
                }
                $this->innerSet($key, $value);
            } catch (\Throwable $e) {
                $errors->add($e);
            }
        }

        if (!$errors->empty()) {
            throw $errors;
        }

        return $this;
    }

}