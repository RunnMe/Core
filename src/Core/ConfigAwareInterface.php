<?php

namespace Runn\Core;

/**
 * Interface ConfigAwareInterface
 * @package Runn\Core
 */
interface ConfigAwareInterface
{

    /**
     * @param \Runn\Core\Config|null $config
     * @return $this
     *
     * @7.1
     */
    public function setConfig(?Config $config);

    /**
     * @return \Runn\Core\Config|null
     *
     * @7.1
     */
    public function getConfig(): ?Config;

}