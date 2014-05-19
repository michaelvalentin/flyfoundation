<?php

namespace FlyFoundation\Util;
use FlyFoundation\Exceptions\InvalidArgumentException;

/**
 * Class Redirecter
 *
 * A class for redirecting the user to another page
 *
 * @package Util
 */
class Redirecter{

    /**
     * Redirect the user to a given URL
     *
     * @param $url URL to redirect to
     * @param int $type The type of redirect to be done
     * @param array|mixed $urlParameters The URL-parameters to add
     * @throws InvalidArgumentException
     */
    public static function Redirect($url, $type = RedirectType::MovedPermanently, array $urlParameters = []){
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
                throw new InvalidArgumentException("Invalid redirect type, use \\Flyf\\Util\\RedirectType::Constant !");
        }
        if(count($urlParameters)){
            $url .= "?";
            foreach($urlParameters as $p=>$v){
                $url .= "$p=$v&";
            }
            $url = rtrim($url,"&");
        }

        header("Location: ".$url);
        die(); //We have given the redirect -> The rest is up to the browser...!
    }
}

/**
 * Class RedirectType
 *
 * Different types of redirects
 *
 * @package Util
 */
class RedirectType {
    const MovedPermanently = 301;
    const SeeOther = 303;
    const TemporaryRedirect = 307;
}