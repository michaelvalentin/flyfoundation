<?php


namespace FlyFoundation\Core;


use FlyFoundation\Config;
use FlyFoundation\Configurator;
use FlyFoundation\Dependencies\AppDefinition;

class GenericConfigurator implements Configurator{

    use AppDefinition;

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