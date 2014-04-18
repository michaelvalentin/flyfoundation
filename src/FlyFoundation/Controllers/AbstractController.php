<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Environment;
use FlyFoundation\Core\Response;
use FlyFoundation\Core\StandardResponse;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

abstract class AbstractController implements Controller{

    use Environment;

    private $model;
    private $view;
    private $response;

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function setView(View $view)
    {
        $this->view = $view;
    }

    /**
     * @return View
     */
    public function getView(){
        return $this->view;
    }

    public function setBaseResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return StandardResponse
     */
    public function getBaseResponse()
    {
        if($this->response == null){
            $this->response = $this->getFactory()->load("\\FlyFoundation\\Core\\Response");
        }
        return $this->response;
    }
}