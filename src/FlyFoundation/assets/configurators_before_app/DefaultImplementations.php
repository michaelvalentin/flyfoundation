<?php


use FlyFoundation\Core\Config;
use FlyFoundation\Core\Configurator;

class DefaultImplementations implements Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->implementations->putAll([
            "\\FlyFoundation\\Core\\FileLoader" => "\\FlyFoundation\\Core\\StandardFileLoader",
            "\\FlyFoundation\\Core\\Router" => "\\FlyFoundation\\Core\\StandardRouter",
            "\\FlyFoundation\\Controllers\\BaseController" => "\\FlyFoundation\\Controllers\\StandardBaseController"
        ]);
        return $config;
    }
}