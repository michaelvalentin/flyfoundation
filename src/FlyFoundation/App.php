<?php

namespace FlyFoundation;

use FlyFoundation\Core\Factories\SystemDefinitionFactory;
use FlyFoundation\Core\Response;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Controllers\BaseController;
use FlyFoundation\Core\Factories\ConfigurationFactory;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\StandardResponse;
use FlyFoundation\Core\Router;
use FlyFoundation\Exceptions\InvalidConfigurationException;

class App {

    private $configurationFactory;
    private $systemDefinitionFactory;
    private $context;
    /** @var  BaseController */
    private $baseController;

    public function __construct()
    {
        $this->systemDefinitionFactory = new SystemDefinitionFactory();

        $baseConfig = new Config();
        $this->configurationFactory = new ConfigurationFactory($baseConfig);
        $this->configurationFactory->addConfiguratorDirectory(__DIR__."/configurators_before_app");
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
     * @return StandardResponse
     */
    public function getResponse($uri, $context = null)
    {
        $this->prepareCoreDependencies($uri, $context);

        /** @var Router $router */
        $router = Factory::load("\\FlyFoundation\\Core\\StandardRouter");

        $systemQuery = $router->getSystemQuery($uri);

        $baseResponse = $this->getBaseResponse();

        $response = $systemQuery->execute($baseResponse);

        return $this->finalizeResponse($response);
    }

    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function addSystemDirectives($directory)
    {

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

        $baseConfig = $this->configurationFactory->getConfiguration();
        Factory::setConfig($baseConfig);

        $appDefinition = $this->systemDefinitionFactory->getSystemDefinition();
        Factory::setAppDefinition($appDefinition);

        $systemConfigurator = Factory::load("\\FlyFoundation\\Core\\SystemConfigurator");
        $config = $systemConfigurator->configurateWithSystemDefinition($baseConfig, $appDefinition);
        Factory::setConfig($config);
    }

    private function getBaseResponse()
    {
        $response = Factory::load("\\FlyFoundation\\Core\\StandardResponse");
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
            $baseControllerName = Factory::getConfig()->baseController;
            $this->baseController = Factory::load($baseControllerName);
            if(!$this->baseController instanceof BaseController){
                throw new InvalidConfigurationException("The base controller must be of class BaseController.");
            }
        }
        return $this->baseController;
    }
}
