<?php


namespace FlyFoundation\Dependencies;


use FlyFoundation\Core\Context;
use FlyFoundation\Exceptions\UnsetDependencyException;

trait AppContext {

    /** @var Context */
    private $context;

    public function setAppContext(Context $context){
        $this->context = $context;
    }

    /**
     * @return Context
     */
    public function getAppContext(){
        if($this->context == null){
            throw new UnsetDependencyException(
                "The class tried to access an unset dependency (AppContext). Dependencies should be set right after initialization.
            This is easily done with standard dependencies according to the configuration, by loading the dependency via the Factory. If
            this error occurs when loading with the factory, the dependency is not registered in the configuration. In testing and other
            cases where there is valid reason not to use the system default, the dependency can be set via setAppContext"
            );
        }
        return $this->context;
    }
} 