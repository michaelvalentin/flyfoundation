<?php


use FlyFoundation\Core\Config;

class DefaultDirectories implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->pageDirectories->add(__DIR__."/../pages");
        $config->templateDirectories->add(__DIR__."/../templates");
        return $config;
    }
}