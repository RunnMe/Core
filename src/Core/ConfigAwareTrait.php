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

    protected $config;

    /**
     * @param \Runn\Core\Config|null $config
     * @return $this
     */
    public function setConfig(/*?*/Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Runn\Core\Config|null
     */
    public function getConfig(): /*?*/Config
    {
        return $this->config;
    }

}