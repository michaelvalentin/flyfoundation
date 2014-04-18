<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Environment;
use FlyFoundation\Core\Response;
use FlyFoundation\Exceptions\InvalidArgumentException;
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
     * @return Response
     */
    public function getBaseResponse()
    {
        if($this->response == null){
            $this->response = $this->getFactory()->load("\\FlyFoundation\\Core\\Response");
        }
        return $this->response;
    }

    /**
     * @param $action
     * @param array $arguments
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     * @return Response
     */
    public function render($action, array $arguments = [])
    {
        $result = $this->doAction($action, $arguments);
        if(!$result){
            throw new InvalidArgumentException("The controller does not respond to the given arguments, and hence can
            not render. Check with the repondsTo method before calling render.");
        }
        return $result;
    }

    /**
     * @param $action
     * @param array $arguments
     * @return bool
     */
    public function respondsTo($action, array $arguments = [])
    {
        return $this->doAction($action, $arguments) != false;
    }

    /**
     * @param $action
     * @param $arguments
     * @return bool|Response
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    private function doAction($action, $arguments)
    {
        if(!method_exists($this,$action)){
            throw new InvalidArgumentException("The given action '".$action."' could not be found in the controller.");
        }

        $response = $this->getBaseResponse();

        return $this->$action($response, $arguments);
    }
}