<?php

namespace Controllers\Specials;
use Controllers\Abstracts\AbstractController;
use Controllers\Abstracts\AbstractJsonContentController;
use Core\Response;
use Exceptions\InvalidArgumentException;


/**
 * Class PageController
 *
 * Displays a page, consisting of a phtml-file with the content and a JSON file with relevant data
 *
 * @package Controllers\Specials
 */
class PageController extends AbstractJsonContentController{

    public function RenderGet(Response $response)
    {
        if($this->args[0] == "404notfound"){
            header("HTTP/1.1 404 Not Found");
        }

        $filename = BASEDIR.DS."..".DS."content".DS."pages".DS.implode(DS,$this->args).".phtml";
        $var_filename = BASEDIR.DS."..".DS."content".DS."pages".DS.implode(DS,$this->args).".json";
        if(!is_file($filename)){
            if($this->args[0] == "404notfound") throw new InvalidArgumentException("The 404notfound page could not be found in ".$filename);
            $this->args = array("404notfound");
            return $this->RenderGet($response);
        }
        $this->template = "page";
        $response->SetContent(file_get_contents($filename));
        if(is_file($var_filename)){
            $response->AddData(json_decode(file_get_contents($var_filename),true));
        }
        return $response;
    }
}