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

        $this->getBaseController()->beforeApp();

        Factory::getConfig()->lock();

        /** @var Router $router */
        $router = Factory::load("\\FlyFoundation\\Core\\Router");
        $systemQuery = $router->getSystemQuery();

        $this->getBaseController()->beforeController($systemQuery);
        $systemQuery->execute();
        $this->getBaseController()->afterController($systemQuery);

        $this->getBaseController()->afterApp();

        return Factory::getConfig()->dependencies->get("FlyFoundation\\Dependencies\\AppResponse")[0];
    }

    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }

    public function prepareCoreDependencies(Context $context)
    {
        $this->prepareConfig();
        $this->prepareContext($context);
        $this->prepareResponse();
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

    private function prepareResponse()
    {
        $response = Factory::load("\\FlyFoundation\\Core\\Response");
        Factory::getConfig()->dependencies->putDependency(
            "FlyFoundation\\Dependencies\\AppResponse",
            $response,
            true
        );
    }

    /**
     * @throws Exceptions\InvalidConfigurationException
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