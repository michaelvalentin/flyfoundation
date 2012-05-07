<?php

/**
 * A general model for composing http responses
 * 
 * @author Michael Valentin <mv@signifly.com>    
 */
namespace Flyf\Core;

use \Flyf\Core\Request as Request;
use \Flyf\Core\Response\ResponseHeaders;
use \Flyf\Core\Response\ResponseMetaData;

use Flyf\Util\Set as Set;

class Response {
	public $Headers;
	public $MetaData;
	public $Title;
	private $_doctype;
	private $_javascripts;
	private $_stylesheets;
	public $CompressOutput = true;
	private $_controller;	
	private static $_responses = array();
	
	
	/**
	 * Initiate a new response with default values
	 */
	private function __construct(){
		$this->Headers = new ResponseHeaders();
		$this->MetaData = new ResponseMetaData();
		$this->_javascripts = new Set();
		$this->_stylesheets = new Set();
		$defaultComponent = Config::GetValue("default_component");
		$this->_controller = \Flyf\Util\ComponentLoader::LoadController($defaultComponent);
		$this->_doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		if(DEBUG) $this->_doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'; 
	}
	
	
	/**
	 * Response factory. Returns a Response corresponding to the given key; a new Response
	 * if no response has been made for this key yet.
	 * 
	 * @param string $key The key.
	 * @return \Flyf\Core\Response Response corresponding to the given key.
	 */
	public static function GetResponse($key="default"){
		if(isset(self::$_responses[$key])){
			return self::$_responses[$key];
		}else{
			$newResponse = new Response();
			self::$_responses[$key] = $newResponse;
			return $newResponse;
		}
	}
	
	/**
	 * Set the content type in both headers and metadata
	 * 
	 * @param string $type (The type eg. text/html)
	 * @param string $charset (The charset eg. utf-8)
	 */
	public function SetContentType($type="text/html",$charset="utf-8"){
		$this->Headers->SetHeader("Content-Type",$type."; ".$charset);
		$this->MetaData->Set("content-type",$type."; charset=".strtoupper($charset));
	}
	
	/**
	 * Get the front controller of the response
	 * 
	 * @return Flyf\Components\Abstracts\AbstractController 
	 */
	public function GetController() {
		return $this->_controller;
	}

	/**
	 * Set the front controller of the response
	 * 
	 * @param Flyf\Components\Abstracts\AbstractController $_controller
	 */
	public function SetController(\Flyf\Components\Abstracts\AbstractController $_controller) {
		$this->_controller = $_controller;
	}

	/**
	 * Add this script to the response
	 * 
	 * @param string $js
	 */
	public function AddJs($js) {
		$this->_javascripts->Add($js);
	}
	
	/**
	 * Remove this script from the response
	 * 
	 * @param string $js
	 */
	public function RemoveJs($js) {
		$this->_javascripts->Remove($js);
	}

	/**
	 * Add this stylesheet to the response
	 * 
	 * @param string $css
	 */
	public function AddCss($css) {
		$this->_stylesheets->Add($css);
	}
	
	/**
	 * Remove this stylesheet from the response
	 * 
	 * @param string $script
	 */
	public function RemoveCss($css){
		$this->_stylesheets->Remove($css);
	}

	public function GetJs() {
		return $this->_javascripts->AsArray();
	}

	public function GetCss() {
		return $this->_stylesheets->AsArray();
	}
	
	public function GetDoctype(){
		return $this->_doctype;
	}
	
	public function SetDoctype($doctype){
		$this->_doctype = $doctype;
	}
	
	/**
	 * Output all current contents and send the response
	 */
	public function Output(){
		if ($this->CompressOutput && !DEBUG) { //Never compress in debug mode
			ob_start("ob_gzhandler");
		} else {
			ob_start();
		}
		
		$this->Headers->Output();
		if($this->_controller)
		{
			echo $this->_controller->Render();	
		}
		
		ob_end_flush();
	}
}
