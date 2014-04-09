<?php

namespace FlyFoundation;

use FlyFoundation\Core\ConfigurationFactory;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\Response;
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
     * @return Response
     */
    public function getResponse($query, Context $context = null)
    {
        $config = $this->getConfiguration();

        if($context == null){
            $context = $this->getDefaultContext();
        }

        $factory = new Factory($config, $context);

        /** @var Router $router */
        $router = $factory->load("\\FlyFoundation\\Core\\Router");

        $controller = $router->getController($query);
        $arguments = $router->getArguments($query);

        return $controller->render($arguments);
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

    public function addConfigurator($path)
    {
        $this->configurationFactory->addConfiguratorDirectory($path);
    }
}
