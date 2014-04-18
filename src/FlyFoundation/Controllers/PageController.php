<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\FileLoader;
use FlyFoundation\Core\Response;
use FlyFoundation\Exceptions\InvalidArgumentException;

class PageController extends AbstractController{

    public function view(Response $response, array $arguments)
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

        $pageContent = file_get_contents($filename);

        $response->setContent($pageContent);

        return $response;
    }

    public function pageNotFound(Response $response, array $arguments)
    {
        $response->headers->SetHeader("HTTP/1.0 404 Not Found",false);
        return $this->view($response, ["alias" => "404-not-found"]);
    }
} 