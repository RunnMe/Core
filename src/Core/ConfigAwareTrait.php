<?php

namespace Runn\Core;

/**
 * ConfigAwareInterface simplest implementation
 *
 * Trait ConfigAwareTrait
 * @package Runn\Core
 */
trait ConfigAwareTrait
{

    /**
     * @var \Runn\Core\Config|null
     */
    protected $config;

    /**
     * @param \Runn\Core\Config $config
     * @return $this
     *
     * @7.1
     */
    public function setConfig(?Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return \Runn\Core\Config
     *
     * @7.1
     */
    public function getConfig(): ?Config
    {
        return $this->config;
    }

}