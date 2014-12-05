<?php


namespace FlyFoundation\Core;


interface Configurator {
    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config);
} 