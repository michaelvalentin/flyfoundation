<?php

namespace Flyf\Util;
use Flyf\Exceptions\InvalidArgumentException;

/**
 * A class for parsing URL's in Flyf
 * 
 * @Package: Flyf\Util
 * @Author: Michael Valentin
 * @Created: 07-08-13 - 09:23
 */
class URLHandler extends Implementation{

    /**
     * Get a Singleton instance of the URLHandler
     *
     * @return URLHandler
     */
    public static function I(){
        return parent::I();
    }

    /**
     * How should a request be parsed into action, controller and parameters?
     *
     * @param $request
     * @return array
     */
    public function Parse($request){
        //Prepare response
        $res = [
            "action" => "",
            "controller" => "",
            "parameters" => array(),
            "filetype" => "html"
        ];

        //TODO: Add support for aliases...

        //Sort out file types
        $filetypes = ["json" => "/\.json$/"];
        foreach($filetypes as $l=>$v){
            if(preg_match($v,$request)){
                $res["filetype"] = $l;
                $request = preg_replace($v,"",$request);
                break;
            }
        }

        //Load from the standard Flyf url-structure
        $parts = explode("/",$request);
        $res["controller"] = "\\Flyf\\Modules\\".str_replace("-","\\",$parts[0])."Controller";
        $res["action"] = isset($parts[1]) ? $parts[1] : "Default";

        return $res;
    }
}