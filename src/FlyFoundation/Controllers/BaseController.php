<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

interface BaseController{
    public function beforeController();

    public function afterController();
}