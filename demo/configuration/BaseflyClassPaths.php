<?php


use FlyFoundation\Core\Configurator;

class BaseflyClassPaths implements Configurator{

    /**
     * @param \FlyFoundation\Core\Config $config
     * @return \FlyFoundation\Core\Config
     */
    public function apply(\FlyFoundation\Core\Config $config)
    {
        $config->modelSearchPaths->add("\\Basefly\\Models");
        $config->viewSearchPaths->add("\\Basefly\\Views");
        $config->controllerSearchPaths->add("\\Basefly\\Controllers");
        $config->databaseSearchPaths->add("\\Basefly\\Database");
        return $config;
    }
}