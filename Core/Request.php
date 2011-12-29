<?php
namespace Flyf\Core;

class Request {
	private static $_requests = array();
	
	private $language;
	private $components;
	
	private $params;

	private function __construct() {
		$this->Configure();
	}

	public static function GetRequest($key = 'default'){
		if(isset(self::$_requests[$key])){
			return self::$_requests[$key];
		}else{
			self::$_requests[$key] = new Request();

			return self::$_requests[$key];
		}
	}

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
	}

	public function GetLanguage() {
		return $this->language ? : Config::GetValue('default_language');
	}
	
	public function getComponents() {
		return $this->components;
	}
	public function GetComponent($index = 'root') {
		return isset($this->components[$index]) ? $this->components[$index] : null;
	}

	public function GetParams() {
		return $this->params;
	}
	public function GetParam($index) {
		return isset($this->params[$index]) ? $this->params[$index] : null;	
	}

	public function GetGet() {
		return $_GET;
	}
	public function GetGetParam($index) {
		return isset($_GET[$index]) ? $_GET[$index] : null;
	}
	public function SetGetParam($index, $value) {
		$_GET[$index] = $value;
	}

	public function GetPost() {
		return $_POST;
	}
	public function GetPostParam($index) {
		return isset($_POST[$index]) ? $_POST[$index] : false;
	}
	public function SetPostParam($index, $value) {
		$_POST[$index] = $value;
	}

	public function GetSession() {
		return $_SESSION; //TODO: Consider db session -> To allow scaling...
	}
	public function GetSessionParam($index) {
		return isset($_SESSION[$index]) ? $_SESSION[$index] : null; //TODO: Consider db session -> To allow scaling...
	}
	public function SetSessionParam($index, $value) {
		$_SESSION[$index] = $value; //TODO: Consider db session -> To allow scaling...
	}

	/*
	public function GetCookie() {
		return $_COOKIE;
	}
	public function GetCookieParam($index) {
		return isset($_COOKIE[$index]) ? $_COOKIE[$index] : false;
	}
	public function SetCookieParam($index, $values) {
		setcookie($values['name'], $values['value'], $values['expire']); //TODO: Consider a response thing?
	}
	*/

	public function GetServer() {
		return $_SERVER;
	}
	public function GetServerParam($index) {
		return isset($_SERVER[strtoupper($index)]) ? $_SERVER[strtoupper($index)] : null;
	}
}
?>
