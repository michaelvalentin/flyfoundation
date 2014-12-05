<?php


class BaseflySettings implements \FlyFoundation\Core\Configurator{

    /**
     * @param \FlyFoundation\Core\Config $config
     * @return \FlyFoundation\Core\Config
     */
    public function apply(\FlyFoundation\Core\Config $config)
    {
        $config->set("offline_mode",false);
        $config->set("globals_path",__DIR__.'/../data/globals.json');
        return $config;
    }
}