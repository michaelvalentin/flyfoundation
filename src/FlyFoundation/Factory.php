<?php


namespace FlyFoundation;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Core\Factories\FactoryTools;
use FlyFoundation\Database\DataFinder;
use FlyFoundation\Database\DataMapper;
use FlyFoundation\Database\DataMethods;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Exceptions\UnknownClassException;
use FlyFoundation\Models\Model;
use FlyFoundation\Util\ClassInspector;
use FlyFoundation\Views\View;

class Factory {

    /** @var Config */
    private static $config;
    private static $context;

    private static $singletons = [];

    public static function setConfig(Config $config)
    {
        self::$config = $config;
    }

    /**
     * @return Config
     */
    public static function getConfig()
    {
        if(self::$config == null){
            throw new InvalidOperationException("The factory must be supplied with a configuration before it can be used.");
        }
        return self::$config;
    }

    /**
     * @param mixed $appDefinition
     */
    public static function setAppDefinition($appDefinition)
    {
        self::$appDefinition = $appDefinition;
    }

    /**
     * @return mixed
     */
    public static function getAppDefinition()
    {
        if(self::$appDefinition == null){
            throw new InvalidOperationException("The factory must be supplied with an AppDefinition before it can be used.");
        }
        return self::$appDefinition;
    }

    /**
     * @param mixed $context
     */
    public static function setContext($context)
    {
        self::$context = $context;
    }

    /**
     * @return mixed
     */
    public static function getContext()
    {
        if(self::$context == null){
            throw new InvalidOperationException("The factory must be supplied with a context before it can be used.");
        }
        return self::$context;
    }
    private static $appDefinition;

    /**
     * @param string $actualClassName
     * @param array $arguments
     * @return object
     */
    public static function load($className, array $arguments = array())
    {

        $specializedFactory = self::findSpecializedFactory($className);

        if($specializedFactory){
            $result =  $specializedFactory->load($className, $arguments);
        }else{
            $result = self::loadAndDecorateWithoutSpecialization($className, $arguments);
        }

        if(class_exists($className)){
            if(!$result instanceof $className){
                throw new InvalidClassException("The class '".get_class($result)."' is used as '".$className."' but does not extend it. This is not allowed.");
            }
        }

        return $result;
    }

    public static function exists($className)
    {

        $specializedFactory = self::findSpecializedFactory($className);

        if($specializedFactory){
            return $specializedFactory->exists($className);
        }

        return class_exists($className);
    }

    /**
     * @param $className
     * @return bool|AbstractFactory
     */
    private static function findSpecializedFactory($className)
    {
        $factorySearchPathsMap = [
            "ControllerFactory" => self::getConfig()->controllerSearchPaths,
            "DatabaseFactory" => self::getConfig()->databaseSearchPaths,
            "ModelFactory" => self::getConfig()->modelSearchPaths,
            "ViewFactory" => self::getConfig()->viewSearchPaths
        ];

        foreach($factorySearchPathsMap as $factory=>$paths)
        {
            $partialClassName = FactoryTools::findPartialClassNameInPaths($className,$paths);

            if($partialClassName){
                return self::load("\\FlyFoundation\\Core\\Factories\\".$factory);
            }
        }

        return false;
    }

    public static function loadAndDecorateWithoutSpecialization($className, $arguments)
    {
        if(!class_exists($className)){
            throw new UnknownClassException('Class "'.$className.'" was not found by the auto-loading mechanism');
        }

        $reflectionObject = new \ReflectionClass($className);
        $classInstance = $reflectionObject->newInstanceArgs($arguments);

        $classInstance = self::addDependencies($classInstance);

        return $classInstance;
    }

    private static function addDependencies($instance)
    {
        $instanceTraits = ClassInspector::classUsesDeep($instance);
        $dependencies = self::getConfig()->dependencies->asArray();

        //Core dependencies
        if(in_array("FlyFoundation\\Dependencies\\AppConfig",$instanceTraits)){
            $instance->setAppConfig(self::getConfig());
            $instanceTraits = array_diff($instanceTraits,["\\FlyFoundation\\Dependencies\\AppConfig"]);
        }
        if(in_array("FlyFoundation\\Dependencies\\AppContext",$instanceTraits)){
            $instance->setAppContext(self::getContext());
            $instanceTraits = array_diff($instanceTraits,["\\FlyFoundation\\Dependencies\\AppContext"]);
        }
        if(in_array("FlyFoundation\\Dependencies\\AppDefinition",$instanceTraits)){
            $instance->setAppDefinition(self::getAppDefinition());
            $instanceTraits = array_diff($instanceTraits,["\\FlyFoundation\\Dependencies\\AppDefinition"]);
        }

        //Other dependencies
        foreach($instanceTraits as $traitName)
        {
            if(isset($dependencies[$traitName])){
                $traitNameParts = explode("\\",$traitName);
                $traitLastName = array_pop($traitNameParts);
                $dependency = self::getInstance($dependencies[$traitName][0], $$dependencies[$traitName][1]);
                $dependency = self::addDependencies($dependency);
                $setterMethodName = "set".$traitLastName;
                $instance->$setterMethodName($dependency);
            }
        }

        return $instance;
    }

    private static function getInstance($instance, $singleton)
    {
        if($singleton){
            return $instance;
        }else{
            return clone $instance;
        }
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
    public static function loadView($viewName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Views\\".$viewName."View";
        return self::load($fullClassName, $arguments);
    }

    public static function viewExists($viewName)
    {
        $fullClassName = "\\FlyFoundation\\Views\\".$viewName."View";
        return self::exists($fullClassName);
    }

    /**
     * @param string $controllerName
     * @param array $arguments
     * @return Controller
     */
    public static function loadController($controllerName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Controllers\\".$controllerName."Controller";
        return self::load($fullClassName, $arguments);
    }

    public static function controllerExists($controllerName)
    {
        $fullClassName = "\\FlyFoundation\\Controllers\\".$controllerName."Controller";
        return self::exists($fullClassName);
    }

    /**
     * @param string $modelName
     * @param array $arguments
     * @return Model
     */
    public static function loadModel($modelName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName;
        return self::load($fullClassName, $arguments);
    }

    public static function modelExists($modelName)
    {
        $fullClassName = "\\FlyFoundation\\Models\\".$modelName;
        return self::exists($fullClassName);
    }

    /**
     * @param string $entityName
     * @param array $arguments
     * @return DataMapper
     */
    public static function loadDataMapper($entityName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataMapper";
        return self::load($fullClassName, $arguments);
    }

    public static function dataMapperExists($entityName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataMapper";
        return self::exists($fullClassName);
    }

    /**
     * @param string $entityName
     * @param array $arguments
     * @return DataFinder
     */
    public static function loadDataFinder($entityName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataFinder";
        return self::load($fullClassName, $arguments);
    }

    public static function dataFinderExists($entityName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$entityName."DataFinder";
        return self::exists($fullClassName);
    }

    /**
     * @param string $dataMethodsName
     * @param array $arguments
     * @return DataMethods
     */
    public static function loadDataMethods($dataMethodsName, $arguments = array())
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$dataMethodsName;
        return self::load($fullClassName, $arguments);
    }

    public static function dataMethodsExists($dataMethodsName)
    {
        $fullClassName = "\\FlyFoundation\\Database\\".$dataMethodsName;
        return self::exists($fullClassName);
    }
}
