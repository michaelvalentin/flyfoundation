<?php


use FlyFoundation\Core\Configurator;

class BaseflyRoutings implements Configurator{

    /**
     * @param \FlyFoundation\Core\Config $config
     * @return \FlyFoundation\Core\Config
     */
    public function apply(\FlyFoundation\Core\Config $config)
    {
        $config->routing->addRouting("GET:demo","Page#view",["alias"=>"basefly-demo"]);
        $config->routing->addRouting("GET:demoform","Demo#create");
        $config->routing->addRouting("GET:form","Form#view");
        $config->routing->addRouting("POST:form","Form#view");

        return $config;
    }
}