<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Response;

interface BaseController{
    public function beforeController(Response $response);

    public function afterController(Response $response);
}