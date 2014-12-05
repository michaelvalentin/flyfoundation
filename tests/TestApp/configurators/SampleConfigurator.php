<?php


use FlyFoundation\Core\Config;

class SampleConfigurator implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->set("test","ABCDabcd");
        return $config;
    }
}