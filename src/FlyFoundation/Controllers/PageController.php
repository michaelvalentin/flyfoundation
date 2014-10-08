<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\FileLoader;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Factory;

class PageController extends AbstractController{

    use AppConfig;

    public function view(Response $response, array $arguments)
    {
        if(!$this->viewRespondsTo($arguments)){
            throw new InvalidArgumentException("The Page#view action expects an alias as an argument, which has an
            existing page file");
        }

        if($arguments["alias"] == ""){
            $arguments["alias"] = "index";
        }

        /** @var FileLoader $fileLoader */
        $fileLoaderClass = $this->getAppConfig()->getImplementation("\\FlyFoundation\\Core\\FileLoader");
        $fileLoader = Factory::load($fileLoaderClass);
        $filename = $fileLoader->findPage($arguments["alias"]);
        $jsonFilename = $fileLoader->findFile('pages/'.$arguments["alias"].'.json');

        if(!$filename){
            throw new InvalidArgumentException("No page with this name exists.");
        }

        if($jsonFilename){
            $response->setData(json_decode(file_get_contents($jsonFilename), true));
        }

        $pageContent = file_get_contents($filename);

        $response->setContent($pageContent);

        return $response;
    }

    public function viewRespondsTo($arguments){
        if(!isset($arguments["alias"]) || $arguments["alias"] == "index"){
            return false;
        }
        if($arguments["alias"] == ""){
            $arguments["alias"] = "index";
        }

        /** @var FileLoader $fileLoader */
        $fileLoaderClass = $this->getAppConfig()->getImplementation("\\FlyFoundation\\Core\\FileLoader");
        $fileLoader = Factory::load($fileLoaderClass);
        $filename = $fileLoader->findPage($arguments["alias"]);

        if(!$filename){
            return false;
        }

        return true;
    }

    public function pageNotFound(Response $response, array $arguments)
    {
        $response->getHeaders()->SetHeader("HTTP/1.0 404 Not Found",false);
        return $this->view($response, ["alias" => "404-not-found"]);
    }

    public function pageNotFoundRespondsTo(array $arguments)
    {
        //We can always say "page not found" no matter the input arguments...
        return true;
    }
} 