<?php


use FlyFoundation\Core\Config;

class DefaultClassPaths implements FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->modelSearchPaths->add("\\FlyFoundation\\Models");
        $config->viewSearchPaths->add("\\FlyFoundation\\Views");
        $config->controllerSearchPaths->add("\\FlyFoundation\\Controllers");
        $config->databaseSearchPaths->add("\\FlyFoundation\\Database");

        return $config;
    }
}