<?php


class TestAppDirectories implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->pageDirectories->add(__DIR__."/../pages");
        $config->templateDirectories->add(__DIR__."/../templates");
        $config->entityDefinitionDirectories->add(__DIR__."/../entity_definitions");
        $config->entityDefinitionDirectories->add(__DIR__."/../entities");
        $config->templateDirectories->add(__DIR__."/../my_templates");

        return $config;
    }
}