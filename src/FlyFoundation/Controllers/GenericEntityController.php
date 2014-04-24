<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Response;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\Model;

class GenericEntityController extends AbstractController{

    public function index(Response $response, array $arguments)
    {
        $dataFinder = $this->getDataFinderBuilder()->buildIndex($arguments);
        $data = $dataFinder->fetch();

        return $this->assembleResponse($response, $data);
    }

    private function assembleResponse(Response $response, $data)
    {
        $view = $this->getView();
        $view->setData($data);
        $response->setData($view->output());
        return $response;
    }

    public function setModel(Model $model)
    {
        if(!$model instanceof Entity){
            throw new InvalidOperationException("Models for GenericEntityControllers must implement the Entity interface");
        }
        parent::setModel($model);
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->getModel();
    }

    public function getEntityDefinition()
    {
        return $this->getEntity()->getDefinition();
    }

    public function getDataFinderBuilder()
    {
        $dataFinderBuilder = $this->getFactory()->load("\\FlyFoundation\\DefinitionInterpreters\\DataFinderBuilder");
        $dataFinderBuilder->setDefinintion($this->getEntityDefinition());
        return $dataFinderBuilder;
    }
} 