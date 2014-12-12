<?php


class BaseflyDirectories implements \FlyFoundation\Core\Configurator{

    /**
     * @param \FlyFoundation\Core\Config $config
     * @return \FlyFoundation\Core\Config
     */
    public function apply(\FlyFoundation\Core\Config $config)
    {
        $config->pageDirectories->add(__DIR__."/../pages");
        $config->templateDirectories->add(__DIR__."/../templates");
        $config->systemDefinitionDirectories->add(__DIR__."/../lsds");
        return $config;
    }
}