<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Response;

class MyModelController extends AbstractController{
    public function render(array $arguments)
    {
        //TODO: Implement something proper!!!

        /** @var Response $response */
        $response = $this->getBaseResponse();
        $response->setData($this->getModel()->asArray());
        $response->setContent("<p>Dette er MIN en demo, æøå for at se om det hele virker... <b>".$arguments["alias"]."</b></p>");
        $response->wrapInTemplate("<h1>{{test}}</h1>{{{content}}}");
        return $response;
    }

    public function view(array $arguments)
    {
        return $this->render($arguments);
    }

    public function show(array $arguments)
    {
        /** @var Response $response */
        $response = $this->getBaseResponse();
        $response->setContent("<p>HER ER ROOT :-)</p>");
        return $response;
    }
} 