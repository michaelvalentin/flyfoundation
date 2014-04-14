<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

abstract class AbstractController implements Controller{
    private $model;
    private $view;
    private $response;

    public function render(array $arguments)
    {
        $view = $this->getView();
        $model = $this->getModel();
        $response = $this->getResponse();
        $view->setData($model->AsArray());
        $response->AddData($view->output());
        return $response;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

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
}