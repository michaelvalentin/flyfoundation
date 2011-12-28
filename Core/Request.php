<?php
namespace Flyf\Core;

class Request {
	private $language;
	private $component;
	
	private $path;
	private $params;

	public function __construct() {
		$this->configure();
	}

	public function configure() {
		$this->path = $this->getGetParam('path');
		
		$this->language = $this->getGetParam('language');
		$this->component = $this->getGetParam('component');

		$this->params = array();

		$fragments = explode('/', $this->path);

		for ($x = 1; $x < count($fragments); $x++) {
			$this->params[$fragments[$x - 1]] = $fragments[$x];
		}
	}

	public function getLanguage() {
		return $this->language ? : 'default_language'; // TODO from config
	}

	public function getComponent() {
		return $this->component ? : 'default_component'; // TODO from config
	}

	public function getParams() {
		return $this->params;
	}
	public function getParam($index) {
		return isset($this->params[$index]) ? $this->params[$index] : false;	
	}

	public function getGet() {
		return $_GET;
	}
	public function getGetParam($index) {
		return isset($_GET[$index]) ? $_GET[$index] : false;
	}
	public function setGetParam($index, $value) {
		$_GET[$index] = $value;
	}

	public function getPost() {
		return $_POST;
	}
	public function getPostParam($index) {
		return isset($_POST[$index]) ? $_POST[$index] : false;
	}
	public function setPostParam($index, $value) {
		$_POST[$index] = $value;
	}

	public function getSession() {
		return $_SESSION; //TODO: Consider db session -> To allow scaling...
	}
	public function getSessionParam($index) {
		return isset($_SESSION[$index]) ? $_SESSION[$index] : false; //TODO: Consider db session -> To allow scaling...
	}
	public function setSessionParam($index, $value) {
		$_SESSION[$index] = $value; //TODO: Consider db session -> To allow scaling...
	}

	public function getCookie() {
		return $_COOKIE;
	}
	public function getCookieParam($index) {
		return isset($_COOKIE[$index]) ? $_COOKIE[$index] : false;
	}
	public function setCookieParam($index, $values) {
		setcookie($values['name'], $values['value'], $values['expire']); //TODO: Consider a response thing?
	}

	public function getServer() {
		return $_SERVER;
	}
	public function getServerParam($index) {
		return isset($_SERVER[strtoupper($index)]) ? $_SERVER[strtoupper($index)] : false;
	}
}
?>
