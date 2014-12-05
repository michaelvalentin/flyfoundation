<?php


namespace FlyFoundation\Dependencies;

use FlyFoundation\Core\Response;
use FlyFoundation\Exceptions\UnsetDependencyException;

trait AppResponse {
    /** @var Response */
    private $appResponse;

    public function setAppResponse(Response $config){
        $this->appResponse = $config;
    }

    /**
     * @throws UnsetDependencyException
     * @return Response
     */
    public function getAppResponse(){
        if($this->appResponse == null){
            throw new UnsetDependencyException(
                "The class tried to access an unset dependency (AppConfig). Dependencies should be set right after initialization.
            This is easily done with standard dependencies according to the configuration, by loading the dependency via the Factory. If
            this error occurs when loading with the factory, the dependency is not registered in the configuration. In testing and other
            cases where there is valid reason not to use the system default, the dependency can be set via setAppConfig"
            );
        }
        return $this->appResponse;
    }
} 