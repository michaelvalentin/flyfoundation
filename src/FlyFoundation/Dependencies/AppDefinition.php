<?php


namespace FlyFoundation\Dependencies;

use FlyFoundation\Exceptions\UnsetDependencyException;
use FlyFoundation\SystemDefinitions\SystemDefinition;

trait AppDefinition {

    /** @var SystemDefinition */
    private $definition;

    public function setAppDefinition(SystemDefinition $definition){
        $this->definition = $definition;
    }

    /**
     * @return SystemDefinition
     */
    public function getAppDefinition(){
        if($this->definition == null){
            throw new UnsetDependencyException(
                "The class tried to access an unset dependency (AppDefinition). Dependencies should be set right after initialization.
            This is easily done with standard dependencies according to the configuration, by loading the dependency via the Factory. If
            this error occurs when loading with the factory, the dependency is not registered in the configuration. In testing and other
            cases where there is valid reason not to use the system default, the dependency can be set via setAppDefinition"
            );
        }
        return $this->definition;
    }
} 