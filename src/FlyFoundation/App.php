<?php

namespace FlyFoundation;

use FlyFoundation\Core\Factories\ConfigurationFactory;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\StandardResponse;
use FlyFoundation\Util\DirectoryList;
use FlyFoundation\Core\Router;

class App {

    private $configurationFactory;

    public function __construct()
    {
        $baseConfig = new Config();
        $this->configurationFactory = new ConfigurationFactory($baseConfig);

        $defaultConfigurators = __DIR__."/configurators";
        $this->configurationFactory->addConfiguratorDirectory($defaultConfigurators);
    }

    /**
     * @param string $query
     * @param Context $context
     */
    public function serve($query, Context $context = null)
    {
        $this->getResponse($query, $context)->output();
    }

    /**
     * @param string $query
     * @param Context $context
     * @return StandardResponse
     */
    public function getResponse($query, Context $context = null)
    {

        if($context == null){
            $context = $this->getDefaultContext();
        }

        $factory = $this->getFactory($context);

        /** @var Router $router */
        $router = $factory->load("\\FlyFoundation\\Core\\Router",[$context]);

        $controller = $router->getController($query);
        $arguments = $router->getArguments($query);
        $arguments = array();

        return $controller->render($arguments);
    }

    public function getFactory($context = null){
        $config = $this->getConfiguration();

        if($context == null){
            $context = $this->getDefaultContext();
        }

        $factory = new Factory($config, $context);

        return $factory;
    }

    public function getDefaultContext()
    {
        $context = new Context();
        $context->loadFromEnvironment();
        return $context;
    }

    public function getConfiguration()
    {
        return $this->configurationFactory->getConfiguration();
    }

    public function addConfigurators($directory)
    {
        $this->configurationFactory->addConfiguratorDirectory($directory);
    }
}
