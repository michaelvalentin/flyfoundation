<?php
namespace Flyf\Core\Response;

/**
 * A class to represent and output http-headers for a response
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
class ResponseHeaders {
	private $_headers = array();
	
	/**
	 * Create a new set of headers
	 */
	public function __construct(){
		//Default headers..
		$this->SetHeader("Content-Type","text/html; utf-8"); //UTF-8 encoding..
		$this->SetHeader("Expires","-1"); //Don't cache this browser-side...
		$this->SetHeader("Cache-Control","private, max-age=0"); //Don't cache this browser-side..
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
	 * 
	 * @param string $key
	 * @param string $value
	 */
	public function SetHeader($key, $value){
		$this->_headers[$key] = $value;
	}
	
	/**
	 * Clear this header by it's key
	 * 
	 * @param string $key
	 */
	public function ClearHeader($key){
		unset($this->_headers[$key]);
	}
	
	/**
	 * Get the current headers
	 * 
	 * @return array (an array of key=>value for the current headers)
	 */
	public function GetHeaders(){
		return $this->_headers;
	}
}

?>
