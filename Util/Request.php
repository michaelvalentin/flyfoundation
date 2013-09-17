<?php
namespace Flyf\Util;

/**
 * An interpretation of the current request
 *
 * @author Michael Valentin
 */
class Request {
	// Used to hold the request instance
	private static $_request = null;

	private $_controller = true;// The requested controller - if any
    private $_controllerName;   // The interpreted controller
    private $_action;           // The interpreted action
	private $_parameters;       // The interpreted parameters of the request
    private $_baseUrl;          // The base URL for the request (before identifier), might be subdirectory
    private $_requestString;    // The request, after the base URL
    private $_protocol;         // The protocol of the request
    private $_requestFileType;  // The file type of the request

    /**
     * @return mixed
     */
    public function getRequestFileType()
    {
        return $this->_requestFileType;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->_protocol;
    }         // The protocol of the request

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * @return mixed
     */
    public function getRequestString()
    {
        return $this->_requestString;
    }

    /**
     * Return the requested controller
     *
     * @return \Flyf\Modules\iController
     */
    public function getController()
    {
        //If we haven't checked the controller yet, it is neither NULL and no instance of iController
        if($this->_controller !== null && !$this->_controller instanceof \Flyf\Modules\iController)
        {
            //If a class exists with the given name, instansiate it
            if(class_exists($this->_controllerName))
            {
                $this->_controller = new $this->_controllerName;
            }

            //If it does not implement the iController interface, it is not a valid answer
            if(!$this->_controller instanceof \Flyf\Modules\iController)
            {
                $this->_controller = null;
            }
        }
        return $this->_controller;
    }

    /**
     * Get the Class name of the requested controller - existence isn't guaranteed
     *
     * @return string
     */
    public function getControllerName(){
        return $this->_controllerName;
    }

	/**
	 * Private constructor for setting things up
	 */
	private function __construct()
    {
		$this->_parameters = array();
		$this->Parse();
	}

	/**
	 * Get the current request object. Singleton factory.
	 *
	 * @return \Flyf\Util\Request A request instance corresponding to the given key
	 */
	public static function GetRequest()
    {
		if (self::$_request === null) self::$_request = new Request();
		return self::$_request;
	}

	protected function Parse()
    {
        if(preg_match("/HTTPS/",$_SERVER["SERVER_PROTOCOL"])){
            $this->_protocol = "HTTPS";
        }else{
            $this->_protocol = "HTTP";
        }

        //Save the request string
        $this->_requestString = isset($_GET["q"]) ? $_GET["q"] : "";

        //Unset the request string, to make sure that it doesn't turn into a parameter
        unset($_GET["q"]);

        //Determine the baseURL
        $this->_baseUrl = str_replace($this->_requestString,"",$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
        $this->_baseUrl = explode("?",$this->_baseUrl)[0];

        //Determine parameters
        $this->_parameters = $_GET;
        foreach(array_keys($_GET) as $g) unset($_GET[$g]); //Unset the parameters

        //Force lower case URL for SEO purposes
        $lrequest = strtolower($this->_requestString);
        if($this->_requestString != $lrequest){
            Redirecter::I()->Redirect($this->_protocol."://".$this->_baseUrl.$lrequest, RedirectType::MovedPermanently, $this->_parameters);
        }

        //Parse the determined parts
        $ParseResult = URLHandler::I()->Parse($this->_requestString, $_GET);

        //Save the results in the object
        $this->_controllerName = $ParseResult["controller"];
        $this->_action = $ParseResult["action"];
        $this->_requestFileType = $ParseResult["filetype"];
        if(!empty($ParseResult["parameters"])){
            $this->_parameters = array_merge($this->_parameters, $ParseResult["parameters"]);
        }
	}

	/**
	 * Returns the domain of the request.
	 * 
	 * @return the host domain
	 */
	public function GetDomain() {
		$fragments = explode('.', $_SERVER["http_host"]);
		if (count($fragments) > 1) {
			array_pop($fragments);
		}

		return implode('.', $fragments);
	}

	/**
	 * Returns the top level domain of the request.
	 * 
	 * @return the tld of the domain
	 */
	public function GetTLD() {
		$fragments = explode('.', $_SERVER["http_host"]);
		if (count($fragments) > 1) {
			return '.'.array_pop($fragments);
		}

		return null;
	}

    public function AsArray(){
        return [
            "baseURL" => $this->getBaseUrl(),
            "requestString" => $this->getRequestString(),
            "controller" => $this->getController(),
            "action" => $this->getAction(),
            "parameters" => $this->getParameters(),
            "controllerName" => $this->getControllerName(),
            "requestFileType" => $this->getRequestFileType()
        ];
    }
}