<?php


use FlyFoundation\Core\Config;

class DefaultRoutingConfigurator implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->routing->addRouting("GET:", "Page#view",["alias"=>""]);
        $config->routing->addRouting("GET:{alias}", "Page#view");
        return $config;
    }
}