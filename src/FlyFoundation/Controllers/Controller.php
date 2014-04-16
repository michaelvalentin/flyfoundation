<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\StandardResponse;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

interface Controller {
    public function render(array $arguments);

    public function setModel(Model $model);

    public function getModel();

    public function setView(View $view);

    public function getView();

    public function setBaseResponse(StandardResponse $response);
} 