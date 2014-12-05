<?php


use FlyFoundation\Core\Config;

class SecondSampleConfigurator implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->set("test2","This is a demo");
        $config->set("test3","Something else..");
        return $config;
    }
}