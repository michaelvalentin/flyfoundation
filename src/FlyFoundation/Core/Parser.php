<?php

namespace Core;

use Controllers\Specials\PageController;

/**
 * Class Parser
 *
 * Parses requests to return relevant information about how to process the request
 *
 * @package Core
 */
class Parser {

    /**
     * Get a controller from the given request, initialized with relevant arguments
     *
     * @param Request $request
     * @return \Controllers\Abstracts\IController
     */
    public function GetController(\Core\Request $request){
        $args = $request->GetUriParts();

        //Frontpage
        if(!count($args) || trim($args[0]) == ""){
            return new PageController(array("index"));
        }

        //SpecialControllers
        $controller = "\\Controllers\\".ucfirst($args[0])."Controller";
        if(class_exists($controller)){
            array_shift($args);
            return new $controller($args);
        }

        //Pages
        return new PageController($args);
    }
} 