<?php


class ExampleAppConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->set("test","test");
        $config->pageDirectories->add(__DIR__."/../pages");
        $config->templateDirectories->add(__DIR__."/../templates");
        $config->entityDefinitionDirectories->add(__DIR__."/../entity_definitions");
        return $config;
    }
}