<?php


namespace FlyFoundation\Core;


use FlyFoundation\Config;
use FlyFoundation\Configurator;

class GenericConfigurator implements Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        //TODO: Configure app based on system definition...

        return $config;
    }
}