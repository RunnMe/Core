<?php

namespace Runn\Core;

/**
 * Interface ConfigAwareInterface
 * @package Runn\Core
 */
interface ConfigAwareInterface
{

    /**
     * @param \Runn\Core\Config $config
     * @return $this
     *
     * @7.1
     */
    public function setConfig(?Config $config);

    /**
     * @return \Runn\Core\Config
     *
     * @7.1
     */
    public function getConfig(): ?Config;

}