<?php


class DefaultDependencyConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->dependencies->putDependency("AppContext", new \FlyFoundation\Core\Context(), true);
        $config->dependencies->putDependency("AppDefinition", new \FlyFoundation\SystemDefinitions\SystemDefinition(), true);
        return $config;
    }
}