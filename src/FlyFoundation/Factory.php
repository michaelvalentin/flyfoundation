<?php


namespace FlyFoundation;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\Environment;
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
     * @param $className
     * @return mixed
     */
    public function load($className)
    {
        $classNameDots = $this->classNameToDots($className); //Making RegExps prettier

        $matches = [];
        $flyFoundationClass = preg_match("/^(FlyFoundation\.)(.*)$",$classNameDots,$matches);
        if(!flyFoundationClass){
            $this->loadExternalClass($className);
        }
    }

    /**
     * @param $viewName
     * @return View
     */
    public function loadView($viewName)
    {
        $fullClassName = "\\FlyFoundation\\Views\\".$viewName."View";
        return $this->load($fullClassName);
    }

    /**
     * @param $controllerName
     * @return Controller
     */
    public function loadController($controllerName)
    {
        $fullClassName = "\\FlyFoundation\\Controllers\\".$controllerName."Controller";
        return $this->load($fullClassName);
    }

    /**
     * @param $modelName
     * @return Model
     */
    public function loadModel($modelName)
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName."Model";
        return $this->load($fullClassName);
    }

    public function loadEntityForm($modelName)
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName."EntityForm";
        return $this->load($fullClassName);
    }

    public function loadEntityListing($modelName)
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName."EntityListing";
        return $this->load($fullClassName);
    }

    public function loadDataMapper($modelName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$modelName."DataMapper";
        return $this->load($fullClassName);
    }

    public function loadDataFinder($modelName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$modelName."DataFinder";
        return $this->load($fullClassName);
    }

    public function loadDataQueryObject($dqoName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$dqoName;
        return $this->load($fullClassName);
    }

    private function classNameToDots($className)
    {
        $parts = explode("\\",$className);
        if($parts[0] == ""){
            array_shift($parts);
        }
        return implode(".",$parts);
    }

    private function classNameFromDots($classNameDots)
    {
        $parts = explode(".",$classNameDots);
        return "\\".implode("\\",$parts);
    }

    private function loadExternalClass($className)
    {
        $config = $this->getConfig();
        if($config->classOverrides->hasKey($className)){
            $className = $config->get($className);
        }
    }
} 