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
use FlyFoundation\Exceptions\UnknownClassException;
use FlyFoundation\Models\Model;
use FlyFoundation\Util\ClassInspector;
use FlyFoundation\Util\ValueList;
use FlyFoundation\Views\View;

class Factory extends AbstractFactory{

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

        $implementation = $this->getOverride($className);
        if($implementation == $className){
            $className = $this->findImplementation($className,$this->getConfig()->baseSearchPaths);
        }else{
            $className = $implementation;
        }

        $specializedFactory = $this->findSpecializedFactory($className);

        if($specializedFactory){
            return $specializedFactory->load($className, $arguments);
        }else{
            return $this->loadWithoutOverridesAndDecoration($className, $arguments);
        }

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
            "ViewFactory" => $this->getConfig()->viewSearchPaths,
            "EntityDefinitionFactory" => new ValueList(["\\FlyFoundation\\SystemDefinitions"])
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
            $instance->setConfig($this->getConfig());
            $instance->setContext($this->getContext());
        }
        return $instance;
    }

    /*****
     *****
     *
     * FROM HERE:
     * Specialized methods for easy type management...
     *
     *****
     *****/

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
     * @param string $entityName
     * @param array $arguments
     * @return DataMapper
     */
    public function loadDataMapper($entityName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataMapper";
        return $this->load($fullClassName, $arguments);
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

    /**
     * @param string $dqoName
     * @param array $arguments
     * @return DataMethods
     */
    public function loadDataMethods($dqoName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$dqoName;
        return $this->load($fullClassName, $arguments);
    }

    public function loadEntityDefinition($entityName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\SystemDefinitions\\".$entityName."Definition";
        return $this->load($fullClassName, $arguments);
    }
}
