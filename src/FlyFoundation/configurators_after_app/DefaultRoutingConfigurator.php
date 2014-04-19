<?php


class DefaultRoutingConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->routing->addRouting("GET:", "Page#view",["alias"=>"index"]);
        $config->routing->addRouting("GET:{alias}", "Page#view");
        return $config;
    }
}