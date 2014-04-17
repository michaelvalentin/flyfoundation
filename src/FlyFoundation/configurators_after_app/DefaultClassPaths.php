<?php


class DefaultClassPaths implements FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->baseSearchPaths->add("\\FlyFoundation");

        return $config;
    }
}