<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\FileLoader;
use FlyFoundation\Core\Response;
use FlyFoundation\Exceptions\InvalidArgumentException;

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
        if(!isset($arguments["alias"])){
            throw new InvalidArgumentException("The page controller expects an alias for the page and can't load without.");
        }

        /** @var FileLoader $fileLoader */
        $fileLoader = $this->getFactory()->load("\\FlyFoundation\\Core\\FileLoader");
        $filename = $fileLoader->findPage($arguments["alias"]);

        if(!$filename){
            throw new InvalidArgumentException("No page with the name given as alias exists.");
        }

        $pageContent = file_get_contents($filename);

        $response = $this->getBaseResponse();
        $response->setContent($pageContent);

        return $response;
    }

    public function respondsTo(array $arguments)
    {
        if(!isset($arguments["alias"])){
            return false;
        }

        /** @var FileLoader $fileLoader */
        $fileLoader = $this->getFactory()->load("\\FlyFoundation\\Core\\FileLoader");
        $filename = $fileLoader->findPage($arguments["alias"]);

        if(!$filename){
            return false;
        }

        return true;
    }

    public function pageNotFound(array $arguments)
    {
        $this->getBaseResponse()->headers->SetHeader("HTTP/1.0 404 Not Found",false);
        return $this->view(["alias" => "404-not-found"]);
    }
} 