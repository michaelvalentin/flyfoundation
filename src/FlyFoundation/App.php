<?php

namespace FlyFoundation;

use FlyFoundation\Core\Context;
use FlyFoundation\Core\Response;
use FlyFoundation\Util\DirectoryList;
use FlyFoundation\Core\Router;

class App {

    private $configuratorPaths;

    public function __construct(){
        $this->configuratorPaths = new DirectoryList([
            __DIR__."/configs"
        ]);
    }

    /**
     * @param string $query
     * @param Context $context
     */
    public function serve($query, Context $context = null){
        $this->getResponse($query, $context)->Output();
    }

    /**
     * @param string $query
     * @param Context $context
     * @return Response
     */
    public function getResponse($query, Context $context = null){
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

    public function getDefaultContext(){
        $context = new Context();
        $context->loadFromEnvironment();
        return $context;
    }

    public function getConfiguration(){
        //TODO: Implement
        //Run through the configurators and apply them in the right order...
        $config = new Config();
        return $config;
    }

    public function addConfigurator($path){
        $this->configuratorPaths->add($path);
    }
}
