<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppResponse;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Factory;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

abstract class AbstractController implements Controller{

    use AppConfig;
    use AppResponse;

    private $model;
    private $view;

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

    /**
     * @param $action
     * @param array $arguments
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function render($action, array $arguments = [])
    {
        $this->doAction($action, $arguments);
    }

    /**
     * @param $action
     * @param array $arguments
     * @return bool
     */
    public function respondsTo($action, array $arguments = [])
    {
        $respondsToMethod = $action."RespondsTo";
        if(method_exists($this, $respondsToMethod)){
            return $this->$respondsToMethod($arguments);
        }else{
            return $this->doAction($action, $arguments) != false;
        }
    }

    private function doAction($action, $arguments)
    {
        if(!method_exists($this,$action)){
            throw new InvalidArgumentException("The given action '".$action."' could not be found in the controller.");
        }

        return $this->$action($arguments);
    }
}