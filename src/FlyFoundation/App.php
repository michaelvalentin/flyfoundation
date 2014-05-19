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

class App {

    private $configurationFactory;

    public function __construct()
    {
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
        $router = Factory::load("\\FlyFoundation\\Core\\Router");

        $systemQuery = $router->getSystemQuery($uri);

        $baseResponse = $this->getBaseResponse();

        $response = $systemQuery->execute($baseResponse);

        return $this->finalizeResponse($response);
    }

    public function prepareCoreDependencies($uri, $context = null)
    {
        if($context == null){
            $context = new Context();
            $context->loadFromEnvironmentBasedOnUri($uri);
        }
        Factory::setContext($context);
        Factory::setConfig($this->getConfiguration());

        /** @var SystemDefinitionFactory $systemDefinitionFactory */
        $systemDefinitionFactory = Factory::load("\\FlyFoundation\\Core\\Factories\\SystemDefinitionFactory");
        $systemDefinition = $systemDefinitionFactory->createDefinition();

        Factory::setAppDefinition($systemDefinition);

        //TODO: DYNAMIC CONFIGURATIONS...

        Factory::getConfig()->lock();
    }

    public function getConfiguration()
    {

        $this->configurationFactory->addConfiguratorDirectory(__DIR__."/configurators_after_app");
        $config = $this->configurationFactory->getConfiguration();

        return $config;
    }


    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }

    private function getBaseResponse()
    {
        $response = Factory::load("\\FlyFoundation\\Core\\StandardResponse");
        return Factory::getConfig()->baseController->beforeController($response);
    }

    private function finalizeResponse($response)
    {
        return Factory::getConfig()->baseController->afterController($response);
    }
}
