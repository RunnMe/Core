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
     * @param \Throwable|null $previous
     */
    public function __construct(string $id, \Throwable $previous = null)
    {
        $this->id = $id;
        parent::__construct($previous);
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
