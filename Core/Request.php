<?php
namespace Flyf\Core;

class Request {
	private static $_requests = array();
	
	private $language;
	private $component;
	
	private $path;
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
		$this->path = $this->GetGetParam('path');
		
		$this->language = $this->GetGetParam('language');
		$this->component = $this->GetGetParam('component');

		$this->params = array();

		$fragments = explode('/', $this->path);

		for ($x = 1; $x < count($fragments); $x++) {
			$this->params[$fragments[$x - 1]] = $fragments[$x];
		}
	}

	public function GetLanguage() {
		return $this->language ? : Config::GetValue('default_language');
	}

	public function GetComponent() {
		return $this->component ? : Config::GetValue('default_component');
	}

	public function GetParams() {
		return $this->params;
	}
	public function GetParam($index) {
		return isset($this->params[$index]) ? $this->params[$index] : false;	
	}

	public function GetGet() {
		return $_GET;
	}
	public function GetGetParam($index) {
		return isset($_GET[$index]) ? $_GET[$index] : false;
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
		return isset($_SESSION[$index]) ? $_SESSION[$index] : false; //TODO: Consider db session -> To allow scaling...
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
		return isset($_SERVER[strtoupper($index)]) ? $_SERVER[strtoupper($index)] : false;
	}
}
?>
