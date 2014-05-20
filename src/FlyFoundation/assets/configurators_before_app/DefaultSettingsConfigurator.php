<?php


class DefaultSettingsConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->set("app_name","Set the app_name in the configuration! :-)");
        return $config;
    }
}