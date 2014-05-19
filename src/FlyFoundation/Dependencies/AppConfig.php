<?php


namespace FlyFoundation\Dependencies;


use FlyFoundation\Config;
use FlyFoundation\Exceptions\UnsetDependencyException;

trait AppConfig {

    /** @var Config */
    private $config;

    public function setAppConfig(Config $config){
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getAppConfig(){
        if($this->config == null){
            throw new UnsetDependencyException(
                "The class tried to access an unset dependency (AppConfig). Dependencies should be set right after initialization.
            This is easily done with standard dependencies according to the configuration, by loading the dependency via the Factory. If
            this error occurs when loading with the factory, the dependency is not registered in the configuration. In testing and other
            cases where there is valid reason not to use the system default, the dependency can be set via setAppConfig"
            );
        }
        return $this->config;
    }
} 