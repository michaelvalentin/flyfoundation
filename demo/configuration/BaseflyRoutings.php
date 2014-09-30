<?php


use FlyFoundation\Configurator;

class BaseflyRoutings implements Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->routing->addRouting("GET:demo","Page#view",["alias"=>"basefly-demo"]);
        $config->routing->addRouting("GET:form","Form#view");

        return $config;
    }
}