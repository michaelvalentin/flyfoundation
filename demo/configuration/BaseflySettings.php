<?php


class BaseflySettings implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->set("offline_mode",false);
        $config->set("globals_path",__DIR__.'/../data/globals.json');
        return $config;
    }
}