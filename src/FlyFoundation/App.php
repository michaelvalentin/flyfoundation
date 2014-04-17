<?php

namespace FlyFoundation;

use Aws\Common\Exception\InvalidArgumentException;
use FlyFoundation\Core\Factories\ConfigurationFactory;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\StandardResponse;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Util\DirectoryList;
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

        return $systemQuery->execute();
    }

    public function getFactory($context = null){
        if($context == null){
            $context = new Context();
        }

        $config = $this->getConfiguration();

        $factory = new Factory($config, $context);

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

        return $config;
    }

    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }
}
