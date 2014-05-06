<?php


class DefaultEntitiesConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->entityDefinitions->addAll([
            "File",
            "Image",
            "Setting"
        ]);
        return $config;
    }
}