<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Config;
use FlyFoundation\SystemDefinitions\SystemDefinition;

class SystemDefinitionFactory {

    /**
     * @param \FlyFoundation\Config $config
     * @return SystemDefinition
     */
    public function loadFromConfig(Config $config){
        //TODO: Implement
        return new SystemDefinition();
    }

} 