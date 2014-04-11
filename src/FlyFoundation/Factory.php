<?php


namespace FlyFoundation;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\Environment;
use FlyFoundation\Database\DataFinder;
use FlyFoundation\Database\DataMapper;
use FlyFoundation\Database\DataMethods;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

class Factory {
    use Environment;

    public function __construct(Config $config, Context $context)
    {
        $this->setConfig($config);
        $this->setContext($context);
    }

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, $arguments = array())
    {
        $className = $this->getOverride($className);

        $classNameParts = $this->explodeClassName($className);
        $isFlyFoundationClass = $classNameParts[0] == "FlyFoundation";

        if($isFlyFoundationClass){
            return $this->loadFlyFoundationClass($className,$arguments);
        }else{
            return $this->loadClass($className, $arguments);
        }

    }

    /**
     * @param string $viewName
     * @param array $arguments
     * @return View
     */
    public function loadView($viewName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Views\\".$viewName."View";
        return $this->load($fullClassName, $arguments);
    }

    /**
     * @param string $controllerName
     * @param array $arguments
     * @return Controller
     */
    public function loadController($controllerName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Controllers\\".$controllerName."Controller";
        return $this->load($fullClassName, $arguments);
    }

    /**
     * @param string $modelName
     * @param array $arguments
     * @return Model
     */
    public function loadModel($modelName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName."Model";
        return $this->load($fullClassName, $arguments);
    }

    public function loadEntityForm($modelName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName."EntityForm";
        return $this->load($fullClassName, $arguments);
    }

    public function loadEntityListing($modelName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName."EntityListing";
        return $this->load($fullClassName, $arguments);
    }

    /**
     * @param string $modelName
     * @param array $arguments
     * @return DataMapper
     */
    public function loadDataMapper($modelName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$modelName."DataMapper";
        return $this->load($fullClassName, $arguments);
    }

    /**
     * @param string $modelName
     * @param array $arguments
     * @return DataFinder
     */
    public function loadDataFinder($modelName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$modelName."DataFinder";
        return $this->load($fullClassName, $arguments);
    }

    /**
     * @param string $dqoName
     * @param array $arguments
     * @return DataMethods
     */
    public function loadDataQueryObject($dqoName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$dqoName;
        return $this->load($fullClassName, $arguments);
    }

    private function getOverride($className){
        $config = $this->getConfig();
        while($config->classOverrides->hasKey($className))
        {
            $className = $config->get($className);
        }
        return $className;
    }

    private function loadFlyFoundationClass($className, $arguments)
    {
        $parts = $this->explodeClassName($className);
        switch($parts[1]){
            case "Controllers" :
                return $this->loadControllerClass($className,$arguments);
            case "Database" :
                return $this->loadDatabaseClass($className, $arguments);
            case "Models" :
                return $this->loadModelClass($className, $arguments);
            case "Views" :
                return $this->loadViewClass($className, $arguments);
            default :
                return $this->loadClass($className, $arguments);
        }
    }

    private function loadClass($className, $arguments)
    {
        if(!is_class($className)){
            throw new InvalidArgumentException('Class "'.$className.'" was not found by the auto-loading mechanism');
        }

        $reflectionObject = new \ReflectionClass($className);
        $classInstance = $reflectionObject->newInstanceArgs($arguments);

        $classInstance = $this->setEnvironmentVariables($classInstance);

        return $classInstance;
    }

    private function setEnvironmentVariables($instance)
    {
        $traits = class_uses($instance);
        if(in_array("\\FlyFoundation\\Core\\Environment",$traits)){
            /** @var Environment $instance */
            $instance->setFactory($this);
            $instance->setConfig($this->getConfig());
            $instance->setContext($this->getContext());
        }
        return $instance;
    }

    private function explodeClassName($className)
    {
        $parts = explode("\\",$className);
        if($parts[0]==""){
            array_shift($parts);
        }
        return $parts;
    }

    private function loadControllerClass($className, $arguments)
    {
        if(!class_exists($className)){
            $className = $this->getDefaultController($className);
            $this->getEntityDefinition()
        }

        $controller = $this->loadClass($className, $arguments);

        if($controller instanceof Controller){
            $controller = $this->decorateController($controller, $className);
        }

        return $controller;

    }

    private function decorateController(Controller $controller, $className)
    {
        $controllerNaming = "/^(.*)\\\\(.*)Controller/";
        $matches = [];
        $hasControllerNaming = preg_match($controllerNaming, $className, $matches);

        if($hasControllerNaming){
            $controllerName = $matches[2];

            $view = $this->loadView($controllerName);
            $controller->setView($view);

            $model = $this->loadModel($controllerName);
            $controller->setModel($model);
        }

        return $controller;
    }

    private function loadDatabaseClass($className, $arguments)
    {
        $dataInteractorNaming = "/^(.*)\\\\(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataInteractorNaming = preg_match($dataInteractorNaming, $className, $matches);

        if($hasDataInteractorNaming){
            $base = $matches[1];
            $modelName = $matches[2];
            $type = $matches[3];
            $modelNameWithPrefix = $this->getConfig()->get("database_type_class_prefix").$modelName;

            $className = $base.$modelNameWithPrefix.$type;
        }

        $object = $this->loadClass($className, $arguments);

        if($hasDataInteractorNaming){

        }

        return $object;
    }
} 