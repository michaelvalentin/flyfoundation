<?php

namespace FlyFoundation;

use FlyFoundation\Core\Factories\SystemDefinitionFactory;
use FlyFoundation\Core\Response;
use FlyFoundation\Controllers\BaseController;
use FlyFoundation\Core\Factories\ConfigurationFactory;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\Router;
use FlyFoundation\Exceptions\InvalidConfigurationException;

class App {
    /** @var \FlyFoundation\Core\Factories\ConfigurationFactory */
    private $configurationFactory;
    /** @var  BaseController */
    private $baseController;

    public function __construct()
    {
        $this->configurationFactory = new ConfigurationFactory();
        $this->configurationFactory->addConfiguratorDirectory(__DIR__."/assets/configurators_before_app");
    }

    /**
     * @param Context $context
     */
    public function serve(Context $context)
    {
        $this->getResponse($context)->output();
    }

    /**
     * @param Context $context
     * @return Response
     */
    public function getResponse(Context $context)
    {
        $this->prepareCoreDependencies($context);

        //TODO: Here it should be possible to do something dynamic, based on the definition but before the router is used!! EG: Configure the router based on the system definitions?!?! ;-)

        /** @var Router $router */
        $router = Factory::load("\\FlyFoundation\\Core\\Router");

        $systemQuery = $router->getSystemQuery($context);

        $baseResponse = $this->getBaseResponse();

        $response = $systemQuery->execute($baseResponse);

        //TODO: Here it should be possible to do something dynamic, based on the definition, after all other is done..

        return $this->finalizeResponse($response);
    }

    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }

    public function prepareCoreDependencies(Context $context)
    {
        $this->prepareConfig();
        $this->prepareContext($context);
        $this->prepareSystemDefinition();
    }

    private function prepareContext(Context $context)
    {
        Factory::getConfig()->dependencies->putDependency(
            "FlyFoundation\\Dependencies\\AppContext",
            $context,
            true
        );
    }

    private function prepareConfig()
    {
        $this->configurationFactory->addConfiguratorDirectory(__DIR__."/assets/configurators_after_app");
        $config = $this->configurationFactory->getConfiguration();
        $config->lock();
        Factory::setConfig($config);
    }

    private function prepareSystemDefinition()
    {
        $systemDefinitionFactory = new SystemDefinitionFactory();
        $systemDefinitionFactory->setDefinitionDirectories(
            Factory::getConfig()->systemDefinitionDirectories
        );
        $systemDefinition = $systemDefinitionFactory->getSystemDefinition();
        Factory::getConfig()->dependencies->putDependency(
            "FlyFoundation\\Dependencies\\AppDefinition",
            $systemDefinition,
            true
        );
    }

    private function getBaseResponse()
    {
        $response = Factory::load("\\FlyFoundation\\Core\\Response");
        return $this->getBaseController()->beforeController($response);
    }

    private function finalizeResponse($response)
    {
        return $this->getBaseController()->afterController($response);
    }

    /**
     * @return BaseController
     */
    private function getBaseController(){
        if(!isset($this->baseController)){
            $this->baseController = Factory::load("\\FlyFoundation\\Controllers\\BaseController");
            if(!$this->baseController instanceof BaseController){
                throw new InvalidConfigurationException("The base controller must be of class BaseController.");
            }
        }
        return $this->baseController;
    }
}
