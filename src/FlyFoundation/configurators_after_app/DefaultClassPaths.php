<?php


class DefaultClassPaths implements FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->modelSearchPaths->add("\\FlyFoundation\\Models");
        $config->viewSearchPaths->add("\\FlyFoundation\\Views");
        $config->controllerSearchPaths->add("\\FlyFoundation\\Controllers");
        $config->databaseSearchPaths->add("\\FlyFoundation\\Database");

        return $config;
    }
}