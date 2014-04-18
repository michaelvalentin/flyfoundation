<?php


namespace ExampleApp\Controllers;

use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;

class MyModelController extends AbstractController{

    public function view(Response $response, array $arguments)
    {
        if(!isset($arguments["alias"])){
            return false;
        }
        $response->setContent("<p>Your argument for the test controller was: ".$arguments["alias"]."</p>");
        return $response;
    }

    public function show(Response $response, array $arguments)
    {
        $response->setContent("<p>HER ER ROOT :-)</p>");
        return $response;
    }
} 