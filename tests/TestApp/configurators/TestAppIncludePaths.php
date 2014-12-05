<?php


use FlyFoundation\Core\Config;

class TestAppIncludePaths implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->modelSearchPaths->add("\\TestApp\\Models");
        $config->modelSearchPaths->add("\\TestApp\\ExtraModelPath");
        $config->controllerSearchPaths->add("\\TestApp\\Controllers");
        $config->viewSearchPaths->add("\\TestApp\\Views");
        $config->databaseSearchPaths->add("\\TestApp\\Database");
        return $config;
    }
}