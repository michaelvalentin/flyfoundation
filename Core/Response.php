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
		$this->MetaData->SetType($type);
		$this->MetaData->SetCharset(strtoupper($charset));
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

	private function GetJs() {
		$request = Request::GetRequest();
		$jsInternal = array();
		$jsExternal = array();

		foreach($this->_javascripts->AsArray() as $js) {
			if (stripos($js, 'http') === 0) {
				$jsExternal[] = $js;
			} else {
				$jsInternal[] = 'http://'.$request->GetDomain().$request->getTLD().'/'.Config::GetValue('root_path').'/'.$js;
			}
		}

		$jsExternal = array_reverse($jsExternal);
		$jsInternal = array_reverse($jsInternal);

		return array_merge($jsExternal, $jsInternal);
	}

	private function GetCss() {
		$request = Request::GetRequest();
		$cssInternal = array();
		$cssExternal = array();

		foreach($this->_stylesheets->AsArray() as $css) {
			if (stripos($css, 'http') === 0) {
				$cssExternal[] = $css;
			} else {
				$cssInternal[] = 'http://'.$request->GetDomain().$request->getTLD().'/'.Config::GetValue('root_path').'/'.$css;
			}
		}

		$cssExternal = array_reverse($cssExternal);
		$cssInternal = array_reverse($cssInternal);

		return array_merge($cssExternal, $cssInternal);
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
		
		/*echo '<html>'."\r\n";
			echo "\t".'<head>'."\r\n";
				echo "\t"."\t".'<title>'.$this->Title.'</title>'."\r\n"."\r\n";

				foreach($this->GetCss() as $css) {
					echo "\t"."\t".'<link rel="stylesheet" type="text/css" href="'.$css.'" />'."\r\n";	
				}
				echo "\r\n";
				
				foreach($this->GetJs() as $js) {
					echo "\t"."\t".'<script type="text/javascript" src="'.$js.'"></script>'."\r\n";	
				}
				echo "\r\n";
		
				echo $this->MetaData->Output();
			echo "\t".'</head>'."\r\n";
			
			echo "\t".'<body>'."\r\n";
			echo $this->_content;
			echo "\t".'</body>'."\r\n";
		echo '</html>'."\r\n";*/
	}
}
