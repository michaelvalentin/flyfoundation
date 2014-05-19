<?php


class DefaultDependencyConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->classOverrides->putAll([
            "\\FlyFoundation\\Core\\Router"=>"\\FlyFoundation\\Core\\StandardRouter",
            "\\FlyFoundation\\Core\\Response"=>"\\FlyFoundation\\Core\\StandardResponse",
            "\\FlyFoundation\\Core\\FileLoader"=>"\\FlyFoundation\\Core\\StandardFileLoader"
        ]);
        return $config;
    }
}