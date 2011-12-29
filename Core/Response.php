<?php

/**
 * A model to produce HTTP reponses
 * @author MV     
 */
namespace Flyf\Core;

require_once __DIR__.'/ResponseHeaders.php';
require_once __DIR__.'/ResponseMetaData.php';
require_once __DIR__.'/../util/Set.php';

use Flyf\Util\Set as Set;

class Response {
	public $Headers;
	public $MetaData;
	public $Title;
	private $_scripts;
	public $CompressOutput = true;
	private $_content;	
	private static $_responses = array();
	
	
	/**
	 * Initiate a new response with default values
	 */
	private function __construct(){
		$this->Headers = new ResponseHeaders();
		$this->MetaData = new ResponseMetaData();
		$this->_content = "";
		$this->_scripts = new Set();
	}
	
	
	/**
	 * Response factory. Returns a Response corresponding to the given key; a new Response
	 * if no response has been made for this key yet.
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
	 * Get the current content (html-body) of the response
	 * @return string $_content
	 */
	public function GetContent() {
		return $this->_content;
	}

	/**
	 * Set the current content (html-body) of the response
	 * @param string $_content
	 */
	public function SetContent($_content) {
		$this->_content = $_content;
	}

	/**
	 * Add this script to the response
	 * @param string $script
	 */
	public function AddScript($script){
		$this->_scripts->Add($script);
	}
	
	/**
	 * Remove this script from the response
	 * @param string $script
	 */
	public function RemoveScript($script){
		$this->_scripts->Remove($script);
	}
	
	/**
	 * Output all current contents and send the response
	 */
	public function Output(){
		if($this->CompressOutput && !DEBUG){ //Never compress in debug mode
			ob_start("ob_gzhandler");
		}else{
			ob_start();
		}
		$this->Headers->Output();
		echo '<html>';
		echo '<head>';
		echo '<title>'.$this->Title.'</title>';
		foreach($this->_scripts->AsArray() as $script){
			echo '<script type="text/javascript" scr="'.$script.'"></script>';	
		}
		echo $this->MetaData->Output();
		echo '</head>';
		echo '<body>';
		echo $this->_content;
		echo '</body>';
		echo '</html>';
		ob_end_flush();
	}
}

?>
