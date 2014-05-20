<?php


use FlyFoundation\Configurator;

class DefaultImplementations implements Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->implementations->putAll([
            "\\FlyFoundation\\Core\\FileLoader" => "\\FlyFoundation\\Core\\StandardFileLoader",
            "\\FlyFoundation\\Core\\Router" => "\\FlyFoundation\\Core\\StandardRouter",
            "\\FlyFoundation\\Core\\Response" => "\\FlyFoundation\\Core\\StandardResponse",
            "\\FlyFoundation\\Controllers\\BaseController" => "\\FlyFoundation\\Controllers\\StandardBaseController"
        ]);
        return $config;
    }
}