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
     */
    public function setConfig(?Config $config);

    /**
     * @return \Runn\Core\Config|null
     */
    public function getConfig(): ?Config;

}
