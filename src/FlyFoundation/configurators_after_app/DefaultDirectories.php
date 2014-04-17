<?php


class DefaultDirectories implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->entityDefinitionDirectories->add(__DIR__."/../entity_definitions");
        $config->templateDirectories->add(__DIR__."/../templates");

        return $config;
    }
}