<?php


class DefaultClassPaths implements FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->baseSearchPaths->add("\\FlyFoundation");
        $config->modelSearchPaths->add("\\FlyFoundation\\Models");
        $config->databaseSearchPaths->add("\\FlyFoundation\\Database");
        $config->controllerSearchPaths->add("\\FlyFoundation\\Controllers");
        $config->entityDefinitionSearchPaths->add("\\FlyFoundation\\SystemDefinitions");
        $config->viewSearchPaths->add("\\FlyFoundation\\Views");

        return $config;
    }
}