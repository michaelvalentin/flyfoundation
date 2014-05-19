<?php


class TestAppIncludePaths implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->modelSearchPaths->add("\\TestApp\\Models");
        $config->modelSearchPaths->add("\\TestApp\\ExtraModelPath");
        $config->controllerSearchPaths->add("\\TestApp\\Controllers");
        $config->viewSearchPaths->add("\\TestApp\\Views");
        $config->databaseSearchPaths->add("\\TestApp\\Database");
        return $config;
    }
}