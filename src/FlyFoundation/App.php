<?php

namespace FlyFoundation;

use FlyFoundation\Core\Context;
use FlyFoundation\Core\Response;
use FlyFoundation\Util\DirectoryList;
use FlyFoundation\Core\Router;

class App {

    private $configuratorDirectories;

    public function __construct()
    {
        $this->configuratorDirectories = new DirectoryList([
            __DIR__."/configurators"
        ]);
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
        $config = new Config();

        $configurators = [];

        foreach($this->configuratorDirectories->asArray() as $directory)
        {
            $directoryConfigurators = $this->readConfiguratorDirectory($directory);
            $configurators = array_merge($configurators, $directoryConfigurators);
        }

        foreach($configurators as $configurator)
        {
            /** @var Configurator $configurator */
            $config = $configurator->apply($config);
        }

        return $config;
    }

    public function addConfigurator($path)
    {
        $this->configuratorDirectories->add($path);
    }

    private function readConfiguratorDirectory($directory)
    {
        $files = scandir($directory);
        $configurators = [];

        foreach($files as $file){
            $configurator = $this->configuratorFromFile($file,$directory);

            if($configurator){
                $configurators[] = $configurator;
            }
        }

        return $configurators;
    }

    private function configuratorFromFile($file,$directory)
    {
        $matches = [];
        $phpFile = preg_match("/^(.*)(\.php)$/",$file,$matches);
        $className = $matches[1];

        if(!$phpFile){
            return false;
        }

        require $directory."/".$file;

        if(!class_exists($className)){
            return false;
        }

        return new $className();
    }
}
