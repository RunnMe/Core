<?php

namespace Runn\Di;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class ContainerEntryNotFoundException
 * @package Runn\Di
 */
class ContainerEntryNotFoundException extends ContainerException implements NotFoundExceptionInterface
{

    /** @var string */
    protected $id;

    /**
     * ContainerEntryNotFoundException constructor.
     *
     * @param string $id Container entry identifier
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $id, $message = "", $code = 0, \Throwable $previous = null)
    {
        $this->id = $id;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Container entry identifier
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

}
