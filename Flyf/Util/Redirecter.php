<?php

namespace Flyf\Util;

/**
 * TODO: Write class description
 * 
 * @Package: Flyf\Util
 * @Author: Michael Valentin
 * @Created: 08-08-13 - 00:13
 */
class Redirecter extends Implementation{

    /**
     * @return Redirecter
     */
    public static function I(){
        return parent::I();
    }

    public function Redirect($url, $type = RedirectType::MovedPermanently, $urlParameters = false){
        switch($type){
            case 301 :
                header("HTTP/1.1 301 Moved Permanently");
                break;
            case 303 :
                header("HTTP/1.1 303 See Other");
                break;
            case 307 :
                header( "HTTP/1.1 307 Temporary Redirect" );
                break;
            default :
                throw new \Flyf\Exceptions\InvalidArgumentException("Invalid redirect type, use \\Flyf\\Util\\RedirectType::Constant !");
        }
        if($urlParameters){
            $url .= "?";
            $params = is_array($urlParameters) ? $urlParameters : Request::GetRequest()->getParameters();
            foreach($params as $p=>$v){
                $url .= "$p=$v&";
            }
            $url = substr_replace($url,"",-1);
            echo $url;
        }
        header("Location: ".$url);
        die(); //We have given the redirect -> The rest is up to the browser...!
    }
}

class RedirectType {
    const MovedPermanently = 301;
    const SeeOther = 303;
    const TemporaryRedirect = 307;
}