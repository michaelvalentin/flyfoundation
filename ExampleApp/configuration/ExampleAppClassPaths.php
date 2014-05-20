<?php


use FlyFoundation\Configurator;

class ExampleAppClassPaths implements Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->modelSearchPaths->add("\\ExampleApp\\Models");
        $config->viewSearchPaths->add("\\ExampleApp\\Views");
        $config->controllerSearchPaths->add("\\ExampleApp\\Controllers");
        $config->databaseSearchPaths->add("\\ExampleApp\\Database");
        return $config;
    }
}