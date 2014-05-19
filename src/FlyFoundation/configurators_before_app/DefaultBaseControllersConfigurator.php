<?php


class DefaultBaseControllersConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->baseController = new \FlyFoundation\Controllers\StandardBaseController;
        return $config;
    }
}