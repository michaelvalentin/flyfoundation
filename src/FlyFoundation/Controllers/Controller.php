<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

interface Controller {

    /**
     * @param Model $model
     */
    public function setModel(Model $model);

    /**
     * @param View $view
     */
    public function setView(View $view);

    /**
     * @param Response $response
     */
    public function setBaseResponse(Response $response);

    /**
     * @param $action
     * @param array $arguments
     * @return Response
     */
    public function render($action, array $arguments = []);

    /**
     * @param $action
     * @param array $arguments
     * @return bool
     */
    public function respondsTo($action, array $arguments = []);
} 