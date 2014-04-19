<?php


namespace ExampleApp\Controllers;


use FlyFoundation\Controllers\AbstractController;
use FlyFoundation\Core\Response;

class ExampleAppController extends AbstractController{
    public function show(Response $response, array $arguments)
    {
        $response->setContent("<h1>Example app test page</h1><p>Loaded with argument: ".$arguments["word"]."</p>");
        return $response;
    }
} 