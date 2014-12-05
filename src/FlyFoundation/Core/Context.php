<?php
namespace FlyFoundation\Core;

use FlyFoundation\Exceptions\InvalidArgumentException;

class Context {

    private $uri;                      // The request, after the base URL
	private $parameters;               // The get parameters of the request
    private $postData;                 // The post data of the request
    private $fileData;                 // The data about files in the request
    private $serverData;               // The data from the $_SERVER super global
    private $cookieData;               // The cookies supplied with the request

    public function __construct($uri, array $requestData = [])
    {
        $this->parameters = [];
        $this->postData = [];
        $this->fileData = [];
        $this->serverData = [];
        $this->cookieData = [];

        $potentialDataArrays = [
            "server" => "serverData",
            "get" => "parameters",
            "post" => "postData",
            "files" => "fileData",
            "cookie" => "cookieData"
        ];

        foreach($potentialDataArrays as $indexName => $fieldName)
        {
            if(array_key_exists($indexName,$requestData) && is_array($requestData[$indexName])){
                $this->$fieldName = $requestData[$indexName];
            }
        }

        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getHostPath()
    {
        $hostPath = str_replace($this->uri,"",$this->getServerData("HTTP_HOST").$this->getServerData("REQUEST_URI"));
        $hostPath = explode("?",$hostPath)[0];
        $hostPath = preg_replace("/\\/+/","/",$hostPath); //Make all slashes single slash
        return preg_replace("/^(.*)\\/$/","$1",$hostPath); //Remove trailing slash
    }

    public function getBaseUrl()
    {
        return $this->getProtocol()."://".$this->getHostPath();
    }

    public function getProtocol()
    {
        if(preg_match("/HTTPS/",$this->getServerData("SERVER_PROTOCOL"))){
            return "HTTPS";
        }else{
            return "HTTP";
        }
    }

    public function getHttpVerb()
    {
        if($this->getServerData("REQUEST_METHOD") == "POST"){
            return "POST";
        }else{
            return "GET";
        }
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($key, $default = null)
    {
        if(array_key_exists($key, $this->parameters)){
            return $this->parameters[$key];
        }else{
            return $default;
        }
    }

    public function getPostData()
    {
        return $this->postData;
    }

    public function getPostDataValue($key, $default = null)
    {
        if(array_key_exists($key, $this->postData)){
            return $this->postData[$key];
        }else{
            return $default;
        }
    }


    public function getCookies()
    {
        return $this->cookieData;
    }

    public function getCookie($name, $default = null)
    {
        if(array_key_exists($name, $this->cookieData)){
            return $this->cookieData[$name];
        }else{
            return $default;
        }
    }

    public function getDomain()
    {
        return $this->getServerData("HTTP_HOST");
    }

    private function getServerData($key)
    {
        if(array_key_exists($key, $this->serverData)){
            return $this->serverData[$key];
        }else{
            return null;
        }
    }
}