<?php


namespace FlyFoundation;


interface Configurator {
    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config);
} 