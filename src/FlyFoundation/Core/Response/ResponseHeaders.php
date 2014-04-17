<?php
namespace FlyFoundation\Core\Response;


/**
 * Class ResponseHeaders
 *
 * Represents the headers of a response
 *
 * @package Core\Response
 */
class ResponseHeaders {
	private $_headers = array();
	
	/**
	 * Output the current headers
	 */
	public function Output(){
		foreach($this->_headers as $label=>$value){
            if($value){
			    header($label.": ".$value);
            }else{
                header($label);
            }
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
