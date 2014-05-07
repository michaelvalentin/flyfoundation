<?php


namespace FlyFoundation;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Core\Context;
use FlyFoundation\Core\Environment;
use FlyFoundation\Core\Factories\AbstractFactory;
use FlyFoundation\Database\DataFinder;
use FlyFoundation\Database\DataMapper;
use FlyFoundation\Database\DataMethods;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Exceptions\UnknownClassException;
use FlyFoundation\Models\Model;
use FlyFoundation\Util\ClassInspector;
use FlyFoundation\Util\ValueList;
use FlyFoundation\Views\View;

class Factory extends AbstractFactory{

    /**
     * @param string $actualClassName
     * @param array $arguments
     * @return object
     */
    public function load($className, array $arguments = array())
    {

        $actualClassName = $this->findActualClassName($className);

        $specializedFactory = $this->findSpecializedFactory($actualClassName);

        if($specializedFactory){
            $result =  $specializedFactory->load($actualClassName, $arguments);
        }else{
            $result = $this->loadWithoutOverridesAndDecoration($actualClassName, $arguments);
        }

        if(class_exists($className)){
            if(!$result instanceof $className){
                throw new InvalidClassException("The class '".$actualClassName."' is used as '".$className."' but does not extend it. This is not allowed.");
            }
        }

        return $result;
    }

    public function exists($className)
    {
        $className = $this->findActualClassName($className);

        $specializedFactory = $this->findSpecializedFactory($className);

        if($specializedFactory){
            return $specializedFactory->exists($className);
        }

        return class_exists($className);
    }

    private function findActualClassName($className)
    {
        $implementation = $this->getOverride($className);
        if($implementation){
            return $implementation;
        }

        $implementation = $this->findImplementation($className,$this->getConfig()->baseSearchPaths);
        if($implementation){
            return $implementation;
        }

        return $className;
    }

    /**
     * @param $className
     * @return bool|AbstractFactory
     */
    private function findSpecializedFactory($className)
    {
        $factorySearchPathsMap = [
            "ControllerFactory" => $this->getConfig()->controllerSearchPaths,
            "DatabaseFactory" => $this->getConfig()->databaseSearchPaths,
            "ModelFactory" => $this->getConfig()->modelSearchPaths,
            "ViewFactory" => $this->getConfig()->viewSearchPaths
        ];

        foreach($factorySearchPathsMap as $factory=>$paths)
        {
            $partialClassName = $this->findPartialClassNameInPaths($className,$paths);

            if($partialClassName){
                return $this->load("\\FlyFoundation\\Core\\Factories\\".$factory);
            }
        }

        return false;
    }

    public function loadWithoutOverridesAndDecoration($className, $arguments)
    {
        if(!class_exists($className)){
            throw new UnknownClassException('Class "'.$className.'" was not found by the auto-loading mechanism');
        }

        $reflectionObject = new \ReflectionClass($className);
        $classInstance = $reflectionObject->newInstanceArgs($arguments);

        $classInstance = $this->setEnvironmentVariables($classInstance);

        return $classInstance;
    }

    private function setEnvironmentVariables($instance)
    {
        $traits = ClassInspector::classUsesDeep($instance);
        if(in_array("FlyFoundation\\Core\\Environment",$traits)){
            /** @var Environment $instance */
            $instance->setFactory($this);
            if($this->getConfig() != null){
                $instance->setConfig($this->getConfig());
            }
            if($this->getContext() != null){
                $instance->setContext($this->getContext());
            }
            if($this->getAppDefinition() != null){
                $instance->setAppDefinition($this->getAppDefinition());
            }
        }
        return $instance;
    }

    /****************
     ****************
     *****
     *****  FROM HERE AND DOWN:
     *****  Specialized methods for easy loading with type hinting...
     *****
     ****************
     ****************/

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

    public function viewExists($viewName)
    {
        $fullClassName = "\\FlyFoundation\\Views\\".$viewName."View";
        return $this->exists($fullClassName);
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

    public function controllerExists($controllerName)
    {
        $fullClassName = "\\FlyFoundation\\Controllers\\".$controllerName."Controller";
        return $this->exists($fullClassName);
    }

    /**
     * @param string $modelName
     * @param array $arguments
     * @return Model
     */
    public function loadModel($modelName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName;
        return $this->load($fullClassName, $arguments);
    }

    public function modelExists($modelName)
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName;
        return $this->exists($fullClassName);
    }

    /**
     * @param string $entityName
     * @param array $arguments
     * @return DataMapper
     */
    public function loadDataMapper($entityName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataMapper";
        return $this->load($fullClassName, $arguments);
    }

    public function dataMapperExists($entityName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataMapper";
        return $this->exists($fullClassName);
    }

    /**
     * @param string $entityName
     * @param array $arguments
     * @return DataFinder
     */
    public function loadDataFinder($entityName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataFinder";
        return $this->load($fullClassName, $arguments);
    }

    public function dataFinderExists($entityName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataFinder";
        return $this->exists($fullClassName);
    }

    /**
     * @param string $dataMethodsName
     * @param array $arguments
     * @return DataMethods
     */
    public function loadDataMethods($dataMethodsName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$dataMethodsName;
        return $this->load($fullClassName, $arguments);
    }

    public function dataMethodsExists($dataMethodsName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$dataMethodsName;
        return $this->load($fullClassName);
    }
}
