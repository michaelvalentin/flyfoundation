<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Factories\FactoryTools;
use FlyFoundation\Core\FileLoader;
use FlyFoundation\Core\Response;
use FlyFoundation\Database\DataFinder;
use FlyFoundation\Database\DataMapper;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppResponse;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Factory;
use FlyFoundation\Models\Model;
use FlyFoundation\Util\Map;
use FlyFoundation\Views\View;

abstract class AbstractController implements Controller{

    use AppConfig;
    use AppResponse;

    private $model;
    /** @var Map */
    private $views;
    /** @var Map */
    private $templates;
    private $dataMapper;
    private $dataFinder;

    public function __construct()
    {
        $this->views = new Map();
        $this->templates = new Map();
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        if($this->model){
           return $this->model;
        }
        return Factory::loadModel($this->getEntityName());
    }

    /**
     * @param View $view
     * @param string $action
     */
    public function setView(View $view, $action)
    {
        $this->views->put($action,$view);
    }


    /**
     * @param $action
     * @return View
     */
    public function getView($action){
        if($this->views->containsKey($action)){
            return $this->views->get($action);
        }else{
            return Factory::loadView($this->getEntityName(), $action);
        }
    }

    /**
     * @param string $templateName
     * @param string $action
     */
    public function setTemplate($templateName, $action)
    {
        $this->templates->put($action,$templateName);
    }

    /**
     * @param $action
     * @return string
     */
    public function getTemplate($action)
    {
        if($this->templates->containsKey($action)){
            return $this->templates->get($action);
        }else{
            /** @var FileLoader $fileLoader */
            $fileLoader = Factory::load("\\FlyFoundation\\Core\\FileLoader");
            $fileLoader->findTemplate($this->getEntityName().$action);
        }
    }

    /**
     * @param DataMapper $dataMapper
     */
    public function setDataMapper(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    /**
     * @return DataMapper
     */
    public function getDataMapper()
    {
        if($this->dataMapper){
            return $this->dataMapper;
        }
        return Factory::loadDataMapper($this->getEntityName());
    }

    /**
     * @param DataFinder $dataFinder
     */
    public function setDataFinder(DataFinder $dataFinder)
    {
        $this->dataFinder = $dataFinder;
    }

    /**
     * @return DataFinder
     */
    public function getDataFinder()
    {
        if($this->dataFinder){
            return $this->dataFinder;
        }
        return Factory::loadDataFinder($this->getEntityName());
    }

    public function getEntityName()
    {
        $className = "\\".get_called_class();
        $paths = $this->getAppConfig()->controllerSearchPaths;
        $partialClassName = FactoryTools::findPartialClassNameInPaths($className, $paths);
        $matches = [];
        preg_match("/^(?<entityname>.*)Controller/",$partialClassName,$matches);
        if(isset($matches["entityname"])){
            return $matches["entityname"];
        }else{
            return null;
        }
    }

    /**
     * @param $action
     * @param array $arguments
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function render($action, array $arguments = [])
    {
        if(!method_exists($this,$action)){
            throw new InvalidArgumentException(
                "The given action '".$action."' could not be found in the controller '".get_called_class()."'."
            );
        }

        return $this->$action($arguments);
    }

    /**
     * @param $action
     * @param array $arguments
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     * @return bool
     */
    public function respondsTo($action, array $arguments = [])
    {
        $respondsToMethod = $action."RespondsTo";
        if(!method_exists($this, $respondsToMethod)){
            throw new InvalidArgumentException(
                "The given action '".$action."' did not have a 'respondsTo' method declared in the controller '".get_called_class()."'."
            );
        }

        return $this->$respondsToMethod($arguments);
    }
}