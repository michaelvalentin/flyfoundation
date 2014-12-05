<?php


use FlyFoundation\Core\Config;

class DefaultSettingsConfigurator implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->set("app_name","Set the app_name in the configuration! :-)");
        return $config;
    }
}