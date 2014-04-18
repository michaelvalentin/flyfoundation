<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Response;

class MyModelController extends AbstractController{

    public function view(Response $response, array $arguments)
    {
        $response->setContent("<p>HER ER ROOT :-)</p>");
        return $response;
    }

    public function show(Response $response, array $arguments)
    {
        $response->setContent("<p>HER ER ROOT :-)</p>");
        return $response;
    }
} 