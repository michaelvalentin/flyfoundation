<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Response;

class PageController extends AbstractController{
    public function render(array $arguments)
    {
        //TODO: Implement something proper!!!

        /** @var Response $response */
        $response = $this->getBaseResponse();
        $response->setData(["test"=>"Hello World"]);
        $response->setContent("<p>Dette er bare en demo, æøå for at se om det hele virker...</p>");
        $response->wrapInTemplate("<h1>{{test}}</h1>{{{content}}}");
        return $response;
    }
} 