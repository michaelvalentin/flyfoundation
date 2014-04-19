<?php


use FlyFoundation\Configurator;

class ExampleAppRoutings implements Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->routing->addRouting("GET:example-app/{word}","ExampleApp#show");
        $config->routing->addRouting("GET:test/{alias}","MyModel#view");
        $config->routing->addRouting("GET:test/", "MyModel#show");
        $config->routing->addRouting("GET:blog", "BlogPost#index");
        return $config;
    }
}