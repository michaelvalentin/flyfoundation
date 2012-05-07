<?php
namespace Flyf\Core;

use \Flyf\Models\Url\Rewrite as Rewrite;
use \Flyf\Util\Debug as Debug;

/**
 * The Request class interprets and arranges
 * the request sent from the client to the server.
 *
 * It's primary focus is to interpret the global
 * GET variable, but it should also be used to 
 * access POST, SERVER and SESSION variables throughout
 * the application for consistensy.
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
class Request {
	// Used to hold the different request instances
	private static $_requests = array();

	
	private $_request; // The full request
	private $_component; // The called component
	private $_parameters; // The parameters of the request
	private $_lang_iso;

	/**
	 * Initially call the Configure method to
	 * interpret and rearrange the globals.
	 *
	 */
	private function __construct() {
		$this->_parameters = array();
		$this->Parse();
	}

	/**
	 * Factory method for creating instances of the Request
	 * class. Takes one parameter, which is the key to store
	 * the instance under.
	 *
	 * @param string $key (the key to store the instance by)
	 * @return \Flyf\Core\Request (a request instance corresponding to the given key)
	 */
	public static function GetRequest($key = 'default') {
		if (!isset(self::$_requests[$key])) {
			self::$_requests[$key] = new Request();
		}
		
		return self::$_requests[$key];
	}

	public function GetFrontController(){
		return \Flyf\Util\ComponentLoader::LoadController($this->GetComponent(),$this->_parameters);
	}
	
	/**
	 * The method taking care of interpreting and 
	 * rearranging the request in the way we want.
	 *
	 * Will interpret the raw request from the client,
	 * look it up in the database, and if it exists in
	 * the database, it will use the system-request.
	 *
	 * From the system-request the method will extract
	 * the components used, and the parameters belonging
	 * to each component.
	 */
	private function Parse() {
		//Get the raw request
		$this->_request = $this->GetGetParam('request');
		
		//Get the parameters
		$parts = preg_split("&/&",$this->_request);
		$parts = array_filter($parts);
		foreach($parts as $l=>$p)
		{
			$parameter = preg_split("/=/",$p);
			if(count($parameter)>1)
			{
				$this->_parameters[$parameter[0]] = $parameter[1];
				unset($parts[$l]);
			}
		}
		
		//Find out what the language is...
		if(count($parts) && isset($parts[0])){
			if(preg_match("/^[a-z]{2}$/",$parts[0])){ //There is only a language..
				$this->_lang_iso = $parts[0];
				unset($parts[0]);
			}
		}
		
		//Find out what the component is...
		if(count($parts)){
			$component = implode("\\",$parts);
			$this->_component = \Flyf\Util\ComponentLoader::ComponentExists($component) ? $component : Config::GetValue("notfound_component");
		}else{
			$this->_component = Config::GetValue("default_component");
		}
	}

	/**
	 * Returns the protocol of the request.
	 * 
	 * @return the protocol
	 */
	public function GetProtocol() {
		$fragments = explode('/', $this->GetServerParam('server_protocol'));

		return strtolower(array_shift($fragments)).'://';
	}

	/**
	 * Returns the domain of the request.
	 * 
	 * @return the host domain
	 */
	public function GetDomain() {
		$fragments = explode('.', $this->GetServerParam('http_host'));
		if (count($fragments) > 1) {
			array_pop($fragments);
		}

		return implode('.', $fragments);
	}

	/**
	 * Returns the top level domain of the
	 * request.
	 * 
	 * @return the tld of the domain
	 */
	public function GetTLD() {
		$fragments = explode('.', $this->GetServerParam('http_host'));
		if (count($fragments) > 1) {
			return '.'.array_pop($fragments);
		}

		return null;
	}

	/**
	 * If a language is specified in a request, then
	 * it will be returned. If not, the method will
	 * return the default language specified in the
	 * configuration file.
	 *
	 * @return the language of the request
	 */
	public function GetLanguageIso() {
		return $this->_lang_iso;
	}
	
	/**
	 * Returns a specific component from a request, given
	 * the components parameter. See the documentation of
	 * the GetComponents method for example.
	 *
	 * If no component is associated with the key given 
	 * as parameter, then null will be returned.
	 *
	 * @param string $index (the key of the component)
	 * @return string
	 */
	public function GetComponent() {
		return $this->_component;
	}

	/**
	 * Get all parameters as interpreted in the request.
	 * The method can be refined to give only the parameters
	 * of a specied component-key. The method returns an
	 * associative array.
	 *
	 * @param string $component (optional)
	 * @return array (an associative array)
	 */
	public function GetParams($component = null) {
		if ($component != null) {
			if (isset($this->parameters[$component])) {
				return $this->parameters[$component];
			}

			return null;
		} else {
			return $this->parameters;
		}
	}
	
	/**
	 * Method for getting a value of a parameter of a component,
	 * by using the component-key and an index-key. Will return
	 * null if the value does not exists.
	 * 
	 * @param string $component
	 * @param string $index
	 * @return string
	 */
	public function GetParam($component, $index) {
		if (($parameters = $this->GetParams($component)) !== null) {
			if (isset($parameters[$index])) {
				return $parameters[$index];
			}
		}

		return null;
	}

	/**
	 * Proxy method to get the global GET variable.
	 *
	 * @return array
	 */
	public function GetGet() {
		return $_GET;
	}
	
	/**
	 * Proxy method to get an index of the global GET 
	 * variable. Will return null if the index does not exists.
	 *
	 * @param mixed $index
	 * @return array
	 */
	public function GetGetParam($index) {
		return isset($_GET[$index]) ? $_GET[$index] : null;
	}

	/**
	 * Proxy method to set a value to the global GET variable. 
	 *
	 * @param mixed $index
	 * @param mixed $value
	 */	
	public function SetGetParam($index, $value) {
		$_GET[$index] = $value;
	}

	/**
	 * Proxy method to get the global POST variable.
	 *
	 * @return array
	 */
	public function GetPost() {
		return $_POST;
	}

	/**
	 * Proxy method to get an index of the global POST 
	 * variable. Will return null if the index does not exists.
	 *
	 * @param mixed $index
	 * @return array
	 */
	public function GetPostParam($index) {
		return isset($_POST[$index]) ? $_POST[$index] : false;
	}

	/**
	 * Proxy method to set a value to the global POST variable. 
	 *
	 * @param mixed $index
	 * @param mixed $value
	 */	
	public function SetPostParam($index, $value) {
		$_POST[$index] = $value;
	}

	/**
	 * Proxy method to get the global SESSION variable.
	 *
	 * @return array
	 */
	public function GetSession() {
		return $_SESSION; //TODO: Consider db session -> To allow scaling...
	}

	/**
	 * Proxy method to get an index of the global SESSION 
	 * variable. Will return null if the index does not exists.
	 *
	 * @param mixed $index
	 * @return array
	 */
	public function GetSessionParam($index) {
		return isset($_SESSION[$index]) ? $_SESSION[$index] : null; //TODO: Consider db session -> To allow scaling...
	}

	/**
	 * Proxy method to set a value to the global SESSION variable. 
	 *
	 * @param mixed $index
	 * @param mixed $value
	 */	
	public function SetSessionParam($index, $value) {
		$_SESSION[$index] = $value; //TODO: Consider db session -> To allow scaling...
	}

	/**
	 * Proxy method to get the global SERVER variable.
	 *
	 * @return array
	 */
	public function GetServer() {
		return $_SERVER;
	}

	/**
	 * Proxy method to get an index of the global SERVER 
	 * variable. Will return null if the index does not exists.
	 *
	 * @param mixed $index
	 * @return array
	 */
	public function GetServerParam($index) {
		return isset($_SERVER[strtoupper($index)]) ? $_SERVER[strtoupper($index)] : null;
	}
}
