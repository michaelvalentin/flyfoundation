<?php


use FlyFoundation\Configurator;

class BaseflyClassPaths implements Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->modelSearchPaths->add("\\Basefly\\Models");
        $config->viewSearchPaths->add("\\Basefly\\Views");
        $config->controllerSearchPaths->add("\\Basefly\\Controllers");
        $config->databaseSearchPaths->add("\\Basefly\\Database");
        return $config;
    }
}