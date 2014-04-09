<?php


class DefaultOverridesConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->classOverrides->put("\\FlyFoundation\\Core\\Router","\\FlyFoundation\\Core\\DefaultRouter");
        return $config;
    }
}