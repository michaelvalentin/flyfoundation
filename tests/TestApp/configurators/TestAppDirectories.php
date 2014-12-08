<?php


use FlyFoundation\Core\Config;

class TestAppDirectories implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->pageDirectories->add(__DIR__."/../pages");
        $config->templateDirectories->add(__DIR__."/../templates");
        $config->templateDirectories->add(__DIR__."/../my_templates");
        $config->systemDefinitionDirectories->add(__DIR__."/../lsds");

        return $config;
    }
}