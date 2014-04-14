<?php


class TestAppIncludePaths implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->baseSearchPaths->add("\\TestApp");
        $config->modelSearchPaths->add("\\TestApp\\ExtraModelPath");
        return $config;
    }
}