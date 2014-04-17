<?php
namespace FlyFoundation\Core;

use FlyFoundation\Exceptions\InvalidArgumentException;

class Context {

    private $uri;                      // The request, after the base URL
    private $domain;                   // The host / domain
    private $baseUrl;                  // The base URL for the request (before identifier), might be subdirectory
    private $protocol;                 // The protocol of the request
    private $httpVerb;                 // The request method (GET, POST, ETC.)
	private $parameters;               // The get parameters of the request
    private $postData;                 // The post data of the request

    public function __construct(array $initData = array())
    {
        $this->applyDefaults();

        $potentialArguments = [
            "uri",
            "baseUrl",
            "protocol",
            "httpVerb",
            "parameters",
            "postData",
            "domain"
        ];

        foreach($potentialArguments as $argumentName)
        {
            if(array_key_exists($argumentName,$initData)){
                $methodName = "set".ucfirst($argumentName);
                $this->$methodName($initData[$argumentName]);
            }
        }
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getHttpVerb()
    {
        return $this->httpVerb;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($key)
    {
        if(array_key_exists($key, $this->parameters)){
            return $this->parameters[$key];
        }else{
            return null;
        }
    }

    public function getPostData()
    {
        return $this->postData;
    }

    public function getPostDataValue($key)
    {
        if(array_key_exists($key, $this->postData)){
            return $this->postData[$key];
        }else{
            return null;
        }
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function loadFromEnvironmentBasedOnUri($uri)
    {
        $this->setUri($uri);

        $baseUrl = str_replace($this->uri,"",$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        $baseUrl = explode("?",$baseUrl)[0];
        $baseUrl = preg_replace("/\\/+/","/",$baseUrl); //Make all slashes single slash
        $baseUrl = preg_replace("/^(.*)\\/$/","$1",$baseUrl); //Remove trailing slash
        $this->setBaseUrl($baseUrl);

        if(preg_match("/HTTPS/",$_SERVER["SERVER_PROTOCOL"])){
            $this->setProtocol("HTTPS");
        }else{
            $this->setProtocol("HTTP");
        }

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $this->setHttpVerb("POST");
        }else{
            $this->setHttpVerb("GET");
        }

        $this->setParameters($_GET);
        foreach(array_keys($_GET) as $g)
        {
            unset($_GET[$g]);
        }

        $this->setPostData($_POST);
        foreach(array_keys($_POST) as $p)
        {
            unset($_POST[$p]);
        }

        $this->setDomain($_SERVER["HTTP_HOST"]);

    }

    private function setUri($uri)
    {
        $this->uri = $uri;
    }

    private function setBaseUrl($baseUrl)
    {
        if(preg_match("/http(s)?:\\/\\//",$baseUrl)){
            throw new InvalidArgumentException("Base url should not include protocol specification (http://)");
        }
        $this->baseUrl = $baseUrl;
    }

    private function setProtocol($protocol)
    {
        $protocol = strtolower($protocol);
        $allowedProtocols = ["http","https"];
        if(!in_array($protocol,$allowedProtocols)){
            throw new InvalidArgumentException("Only protocol type http and https are allowed");
        }
        $this->protocol = $protocol;
    }

    private function setHttpVerb($httpVerb)
    {
        $httpVerb = strtoupper($httpVerb);
        $allowedVerbs = ["GET","POST"];
        if(!in_array($httpVerb,$allowedVerbs)){
            throw new InvalidArgumentException("Only http verbs: GET & POST are allowed");
        }
        $this->httpVerb = $httpVerb;
    }

    private function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    private function setPostData(array $postData)
    {
        $this->postData = $postData;
    }

    private function setDomain($domain)
    {
        $this->domain = $domain;
    }

    private function applyDefaults()
    {
        $this->baseUrl = "";
        $this->httpVerb = "GET";
        $this->parameters = [];
        $this->postData = [];
        $this->protocol = "HTTP";
        $this->uri = "";
        $this->domain = "";
    }
}