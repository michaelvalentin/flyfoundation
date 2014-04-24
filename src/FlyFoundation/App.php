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
        if($context == null){
            $context = new Context();
            $context->loadFromEnvironmentBasedOnUri($uri);
        }

        $factory = $this->getFactory($context);

        /** @var Router $router */
        $router = $factory->load("\\FlyFoundation\\Core\\Router");

        $systemQuery = $router->getSystemQuery($uri);

        $baseResponse = $this->getBaseResponse($factory);

        $response = $systemQuery->execute($baseResponse);

        return $this->finalizeResponse($response,$factory);
    }

    /**
     * @param Context $context
     * @return Factory
     */
    public function getFactory(Context $context = null)
    {
        $factory = new Factory();

        if($context == null){
            $context = new Context();
        }

        $factory->setContext($context);

        $config = $this->getConfiguration();
        $factory->setConfig($config);

        $systemDefinitionFactory = $factory->load("\\FlyFoundation\\Core\\Factories\\SystemDefinitionFactory");
        $systemDefinition = $systemDefinitionFactory->loadFromConfig($config);
        $factory->setSystemDefinition($systemDefinition);

        return $factory;
    }

    public function getConfiguration()
    {

        $this->configurationFactory->addConfiguratorDirectory(__DIR__."/configurators_after_app");
        $config = $this->configurationFactory->getConfiguration();

        foreach($config->baseSearchPaths->asArray() as $path){
            $config->databaseSearchPaths->add($path."\\Database");
            $config->controllerSearchPaths->add($path."\\Controllers");
            $config->entityDefinitionSearchPaths->add($path."\\SystemDefinitions");
            $config->viewSearchPaths->add($path."\\Views");
            $config->modelSearchPaths->add($path."\\Models");
        }

        foreach($config->baseFileDirectories->asArray() as $dir){
            $config->entityDefinitionDirectories->add($dir."/entity_definitions");
            $config->templateDirectories->add($dir."/templates");
        }

        $config->lock();

        return $config;
    }


    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }

    private function getBaseResponse(Factory $factory)
    {
        /** @var Response $response */
        $response = $factory->load("\\FlyFoundation\\Core\\Response");
        foreach($factory->getConfig()->baseControllers->asArray() as $baseControllerName){
            $baseController = $factory->load($baseControllerName);
            if(!$baseController instanceof BaseController){
                throw new InvalidArgumentException("Base Controllers must be of class AbstractBaseController");
            }
            $response = $baseController->beforeController($response);
        }
        return $response;
    }

    private function finalizeResponse($response, Factory $factory)
    {
        foreach($factory->getConfig()->baseControllers->asArray() as $baseControllerName){
            $baseController = $factory->load($baseControllerName);
            if(!$baseController instanceof BaseController){
                throw new InvalidArgumentException("Base Controllers must be of class AbstractBaseController");
            }
            $response = $baseController->afterController($response);
        }
        return $response;
    }
}
