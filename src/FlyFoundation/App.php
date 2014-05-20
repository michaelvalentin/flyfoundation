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

    private $configurationFactory;
    private $systemDefinitionFactory;
    /** @var  Context */
    private $context;
    /** @var  BaseController */
    private $baseController;

    public function __construct()
    {
        $this->systemDefinitionFactory = new SystemDefinitionFactory();

        $baseConfig = new Config();
        $this->configurationFactory = new ConfigurationFactory($baseConfig);
        $this->configurationFactory->addConfiguratorDirectory(__DIR__."/assets/configurators_before_app");
    }

    /**
     * @param string $uri
     * @param Context $context
     */
    public function serve($uri, $context = null)
    {
        $this->getResponse($uri, $context)->output();
    }

    /**
     * @param string $uri
     * @param Context $context
     * @return Response
     */
    public function getResponse($uri, $context = null)
    {
        $this->prepareCoreDependencies($uri, $context);

        /** @var Router $router */
        $routerClass = Factory::getConfig()->getImplementation("\\FlyFoundation\\Core\\Router");
        $router = Factory::load($routerClass);

        $systemQuery = $router->getSystemQuery($uri);

        $baseResponse = $this->getBaseResponse();

        $response = $systemQuery->execute($baseResponse);

        return $this->finalizeResponse($response);
    }

    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }

    public function prepareCoreDependencies($uri = null)
    {
        if($this->context === null){
            $this->context = new Context();
        }
        if($uri !== null){
            $this->context->loadFromEnvironmentBasedOnUri($uri);
        }
        Factory::setContext($this->context);

        $this->configurationFactory->addConfiguratorDirectory(__DIR__."/assets/configurators_after_app");
        $baseConfig = $this->configurationFactory->getConfiguration();
        Factory::setConfig($baseConfig);

        $this->systemDefinitionFactory->setDirectiveDirectories($baseConfig->entityDefinitionDirectories);
        $appDefinition = $this->systemDefinitionFactory->getSystemDefinition();
        Factory::setAppDefinition($appDefinition);

        $configuratorClass = Factory::getConfig()->getImplementation("\\FlyFoundation\\Core\\GenericConfigurator");
        $configurator = Factory::load($configuratorClass);
        $config = $configurator->apply($baseConfig);
        Factory::setConfig($config);
    }

    private function getBaseResponse()
    {
        $responseClass = Factory::getConfig()->getImplementation("\\FlyFoundation\\Core\\Response");
        $response = Factory::load($responseClass);
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
            $baseControllerClass = Factory::getConfig()->getImplementation("\\FlyFoundation\\Controllers\\BaseController");
            $this->baseController = Factory::load($baseControllerClass);
            if(!$this->baseController instanceof BaseController){
                throw new InvalidConfigurationException("The base controller must be of class BaseController.");
            }
        }
        return $this->baseController;
    }
}
