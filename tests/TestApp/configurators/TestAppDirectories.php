<?php


class TestAppDirectories implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->baseFileDirectories->add(__DIR__."/..");
        $config->entityDefinitionDirectories->add(__DIR__."/../entities");
        $config->templateDirectories->add(__DIR__."/../my_templates");

        return $config;
    }
}