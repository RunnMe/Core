<?php

namespace Runn\Di;

use Psr\Container\ContainerExceptionInterface;
use Runn\Core\Exception;

/**
 * Class ContainerException
 * @package Runn\Di
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{

    /**
     * ContainerException constructor.
     *
     * @param \Throwable|null $previous
     */
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct('', 0, $previous);
    }

}
