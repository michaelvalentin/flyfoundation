<?php
namespace Flyf\Core;

use \Flyf\Models\Url\Rewrite as Rewrite;
use \Flyf\Util\Debug as Debug;

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

	// The request
	private $request;
	// The language of the request
	private $language;
	// THe components of the request
	private $components;
	// The parameters of the request
	private $parameters;

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
	public static function GetRequest($key = 'default') {
		if (!isset(self::$_requests[$key])) {
			self::$_requests[$key] = new Request();
		}
		
		return self::$_requests[$key];
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
	public function Configure() {
		$base = $this->GetProtocol().$this->GetDomain().$this->GetTLD().'/'.Config::GetValue('root_path').'/';
	
		$this->language = $this->GetGetParam('language');
		$this->request = $this->GetGetParam('request');

		$this->components = array();
		$this->parameters = array();

		$seoRequest = $base.$this->request;

		$rewrite = Rewrite::Load(array(
			'seo' => $seoRequest
		));

		if ($rewrite->Exists()) {
			$request = $rewrite->Get('system');
			$request = str_replace($base, '', $request);
		} else {
			Debug::Hint('Rewrite "'.$seoRequest.'" does not exists in database, using request as raw');

			$request = $this->request;
		}

		if ($count = count($components = explode('/', $request))) {
			$components = array_filter($components);
			$prevComponent = 'root';

			if (count($components) == 0) {
				$components = array('root', Config::getValue('root_controller_key'));
			}
			
			foreach ($components as $component) {
				$parameters = array();
				
				if (preg_match_all('/\((.+?)\)/ismu', $component, $matches)) {
					$component = str_replace($matches[0][0], '', $component);

					if ($count = count($fragments = explode('&', $matches[1][0]))) {
						foreach ($fragments as $fragment) {
							$split = explode('=', $fragment);
							$key = $split[0];
							$value = $split[1];

							$parameters[$key] = $value;
						}
					}
				}

				$this->components[$prevComponent] = $component;
				$prevComponent = $component;

				$this->parameters[$component] = $parameters;
			}
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
