<?php
namespace Core;

/**
 * Class Request
 *
 * The current request, as understood by the system, including all necessary data. The
 * request information should be accessed through this class and it's interfaces.
 *
 * @package Core
 */
class Request {
	private static $_request = null;    // Used to hold the request instance
    private $_uri;                      // The request, after the base URL
    private $_baseUrl;                  // The base URL for the request (before identifier), might be subdirectory
    private $_protocol;                 // The protocol of the request
    private $_httpMethod;               // The request method
	private $_parameters;               // The interpreted parameters of the request

    public function getUri(){
        return $this->_uri;
    }

    public function GetUriParts(){
        return explode("/",$this->_uri);
    }

    public function getBaseUrl(){
        return $this->_baseUrl;
    }

    public function getProtocol(){
        return $this->_protocol;
    }

    public function getHttpMethod(){
        return $this->_httpMethod;
    }

    public function getParameters(){
        return $this->_parameters;
    }

    public function getParameter($key){
        return isset($this->_parameters[$key]) ? $this->_parameters[$key] : false;
    }

	/**
	 * Private constructor for setting things up
	 */
	private function __construct($query)
    {
		$this->_parameters = array();
		$this->Parse($query);
	}

    /**
     * What is the current request? (Singleton factory)
     *
     * @return \Core\Request
     * @throws \Exceptions\InvalidOperationException
     */
    public static function getRequest()
    {
		if (self::$_request === null) throw new \Exceptions\InvalidOperationException("Request must be initialized before it can be used.");
		return self::$_request;
	}

    /**
     * Initialize the request from this query
     *
     * @param $query
     * @throws \Exceptions\InvalidOperationException
     */
    public static function Init($query){
        if (self::$_request !== null) throw new \Exceptions\InvalidOperationException("Request can only be initialized once per call.");
        self::$_request = new Request($query);
    }

	protected function parse($query)
    {
        $this->_uri = $query;

        //Determine the baseURL
        $this->_baseUrl = str_replace($this->_uri,"",$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        $parts = explode("?",$this->_baseUrl);
        $this->_baseUrl = $parts[0];
        $parts = explode("/",$this->_baseUrl);
        $last_part = array_pop($parts);
        if($last_part != "") array_push($parts,$last_part);
        $this->_baseUrl = implode("/",$parts);
        $this->_baseUrl = "http://".$this->_baseUrl;

        if(preg_match("/HTTPS/",$_SERVER["SERVER_PROTOCOL"])){
            $this->_protocol = "HTTPS";
        }else{
            $this->_protocol = "HTTP";
        }

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $this->_httpMethod = "POST";
        }else{
            $this->_httpMethod = "GET";
        }

        //Determine parameters
        $this->_parameters = $_GET;
        foreach(array_keys($_GET) as $g) unset($_GET[$g]); //Unset the parameters

        //Force lower case URL for SEO purposes
        $lrequest = strtolower($this->_uri);
        if($this->_uri != $lrequest){
            \Util\Redirecter::Redirect($this->_protocol."://".$this->_baseUrl.$lrequest, \Util\RedirectType::MovedPermanently, $this->_parameters);
        }
	}

    /**
     * Is the current host/domain a demo-domain (where debugging and other insecure stuff is allowed).
     *
     * @return bool
     */
    public function isDemoDomain(){
        return in_array($_SERVER["HTTP_HOST"],\Core\Config::Get("demo_domains"));
    }
}