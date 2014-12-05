<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\FileLoader;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Factory;

class PageController extends AbstractController{

    public function view(array $arguments)
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
        $fileLoader = Factory::loadWithoutImplementationSearch($fileLoaderClass);
        $filename = $fileLoader->findPage($arguments["alias"]);
        $jsonFilename = $fileLoader->findFile('pages/'.$arguments["alias"].'.json');

        if(!$filename){
            throw new InvalidArgumentException("No page with this name exists.");
        }

        if($jsonFilename){
            $this->getAppResponse()->setData(json_decode(file_get_contents($jsonFilename), true));
        }

        $pageContent = file_get_contents($filename);

        $this->getAppResponse()->setContent($pageContent);
    }

    public function viewRespondsTo(array $arguments){
        if(!isset($arguments["alias"]) || $arguments["alias"] == "index"){
            return false;
        }
        if($arguments["alias"] == ""){
            $arguments["alias"] = "index";
        }

        /** @var FileLoader $fileLoader */
        $fileLoaderClass = $this->getAppConfig()->getImplementation("\\FlyFoundation\\Core\\FileLoader");
        $fileLoader = Factory::loadWithoutImplementationSearch($fileLoaderClass);
        $filename = $fileLoader->findPage($arguments["alias"]);

        if(!$filename){
            return false;
        }

        return true;
    }

    public function pageNotFound(array $arguments)
    {
        $this->getAppResponse()->getHeaders()->SetHeader("HTTP/1.0 404 Not Found",false);
        $this->view(["alias" => "404-not-found"]);
    }

    public function pageNotFoundRespondsTo(array $arguments)
    {
        //We can always say "page not found" no matter the input arguments...
        return true;
    }
} 