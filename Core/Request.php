<?php
namespace Flyf\Core;

/**
 *	The Request class interprets and arranges
 * the request sent from the client to the server.
 *
 * It's primary focus is to interpret the global
 * GET variable, but it should also be used to 
 * access POST, SERVER and SESSION variables throughout
 * the application for consistensy.
 * 
 * @author Henrik Haugbølle <hh@signifly.com>
 * @version 2012-01-06
 * @dependencies Config
 */
class Request {
	// Used to hold the different request instances
	private static $_requests = array();

	// The language of the request
	private $language;
	// THe components of the request
	private $components;
	// The parameters of the request
	private $params;

	/**
	 * Initially call the Configure method to
	 * interpret and rearrange the globals.
	 *
	 */
	private function __construct() {
		$this->Configure();
	}

	/**
	 * Factory method for creating instances of the Request
	 * class. Takes one parameter, which is the key to store
	 * the instance under.
	 *
	 * @param string $key (the key to store the instance by)
	 * @return a instance
	 *
	 */
	public static function GetRequest($key = 'default'){
		if(!isset(self::$_requests[$key])) {
			self::$_requests[$key] = new Request();
		}
		
		return self::$_requests[$key];
	}


	/**
	 * The method taking care of interpreting and 
	 * rearranging the request in the way we want.
	 *
	 * @note
	 * by today (2012-01-06) a request looks like this:
	 * language/comp1/comp2:key1=value1,key2=value2
	 *
	 * But it will soon be changed.
	 */
	public function Configure() {
		$this->language = null;
		$this->params = array();
		$this->components = array();

		$this->language = $this->GetGetParam('language');

		if ($params = $this->GetGetParam('params')) {
			$pairs = explode(',', $params);
		
			foreach ($pairs as $pair) {
				$_pair = explode('=', $pair);

				$key = $_pair[0];
				$value = isset($_pair[1]) ? $_pair[1] : null;

				$this->params[$key] = $value;
			}
		}

		if ($components = $this->GetGetParam('components')) {
			$fragments = explode('/', $components);
			array_unshift($fragments, 'root');
		
			for ($x = 1; $x < count($fragments); $x++) {
				$this->components[str_replace('_', '\\', $fragments[$x - 1])] = str_replace('_', '\\', $fragments[$x]);
			}
		}

		$this->components = array('root' => 'blok18', 'blok18' => 'blog', 'blog' => 'list');
		$this->params = array(
			'blok18' => array(
				'secure' => 'secure'
			),
			'blog' => array(

			),
			'list' => array(
				'view' => 'something'
			)
		);
	}

	/**
	 * If a language is specified in a request, then
	 * it will be returned. If not, the method will
	 * return the default language specified in the
	 * configuration file.
	 *
	 * @return the language of the request
	 */
	public function GetLanguage() {
		return $this->language ? : Config::GetValue('default_language');
	}

	/**
	 * Will return all components specified in a request
	 * as an associative array. Each component will be the
	 * next components key (see example below).
	 *
	 * @example
	 * $components = $request->getComponents();
	 * // could return root => shop, shop => product
	 *
	 * @return array
	 */
	public function getComponents() {
		return $this->components;
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
	public function GetComponent($index = 'root') {
		return isset($this->components[$index]) ? $this->components[$index] : null;
	}

	public function GetCurrentComponent() {
		$keys = array_keys($this->components);

		return $this->components[array_pop($keys)];
	}

	/**
	 * Get all parameters as interpreted in the request.
	 * Returns an associate array.
	 *
	 * @return array (an associative array)
	 */
	public function GetParams($component = null) {
		if ($component != null) {
			if (isset($this->params[$component])) {
				return $this->params[$component];
			}

			return null;
		} else {
			return $this->params;
		}
	}
	
	/**
	 * Method for getting a value of a parameter using
	 * its key. Will return null if the key does not exists.
	 *
	 * @param string $index
	 * @return string
	 */
	public function GetParam($index) {
		return isset($this->params[$index]) ? $this->params[$index] : null;	
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
?>
