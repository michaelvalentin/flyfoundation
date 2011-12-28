<?php
namespace Flyf\Core;

/**
 * Simple class to easily produce headers for an http-reponse
 * @author MV
 */
class ResponseHeaders {
	private $_headers = array();
	
	/**
	 * Create a new set of headers
	 */
	public function __construct(){
		//Defaults..
		$this->SetHeader("Content-Type","text/html; utf-8");
		$this->SetHeader("Expires","-1");
		$this->SetHeader("Cache-Control","private, max-age=0");
	}
	
	/**
	 * Output the current headers
	 */
	public function Output(){
		foreach($this->_headers as $l=>$v){
			header($l.": ".$v);
		}
	}
	
	/**
	 * Set a given header
	 * @param string $key
	 * @param string $value
	 */
	public function SetHeader($key, $value){
		$this->_headers[$key] = $value;
	}
}

?>