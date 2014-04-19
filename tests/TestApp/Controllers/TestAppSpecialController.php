<?php


namespace TestApp\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;

class TestAppSpecialController extends AbstractController{
    public function show(Response $response, array $arguments)
    {
        return $response;
    }

    public function delete(Response $response, array $arguments)
    {
        return $response;
    }

    public function showAll(Response $response, array $arguments)
    {
        return $response;
    }

    public function showFrontPage(Response $response, array $arguments)
    {
        return $response;
    }
} 