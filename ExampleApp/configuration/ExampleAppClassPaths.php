<?php


use FlyFoundation\Configurator;

class ExampleAppClassPaths implements Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->baseSearchPaths->add("\\ExampleApp");
        return $config;
    }
}