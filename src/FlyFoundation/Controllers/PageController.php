<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Response;

class PageController extends AbstractController{
    public function render(array $arguments)
    {
        //TODO: Implement something proper!!!

        /** @var Response $response */
        $response = $this->getBaseResponse();
        $response->setData($this->getModel()->asArray());
        $response->setContent("<p>Dette er bare en demo, æøå for at se om det hele virker... <b>".$arguments["alias"]."</b></p>");
        $response->wrapInTemplate("<h1>{{test}}</h1>{{{content}}}");
        return $response;
    }

    public function view(array $arguments)
    {
        return $this->render($arguments);
    }

    public function pageNotFound(array $arguments)
    {
        $this->getBaseResponse()->headers->SetHeader("HTTP/1.0 404 Not Found",false);
        return $this->view(["alias" => "404-not-found"]);
    }
} 