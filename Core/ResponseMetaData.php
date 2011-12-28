<?php
namespace Flyf\Core;

require_once __DIR__.'/../util/Set.php';
use Flyf\Util\Set;

/**
 * Simple class to collect meta-data for a http-response
 * @author MV
 */
class ResponseMetaData {
	private $_keyWords;
	
	/**
	 * Create a new response meta data 
	 */
	function __construct() {
		$this->_keyWords = new Set();
	}
	
	/**
	 * Add this keyword to the metadata
	 * @param string $keyword
	 */
	public function AddKeyword($keyword){
		$this->_keyWords->Add($keyword);
	}
	
	/**
	 * Add multiple keywords from array
	 * @param array $keywords
	 */
	public function AddKeywords(array $keywords){
		foreach($keywords as $keyword){
			$this->AddKeyword($keyword);
		}
	}
	
	/**
	 * Remove this keyword
	 * @param string $keyword
	 */
	public function RemoveKeyword($keyword){
		$this->_keyWords($keyword);
	}
	
	/**
	 * Return meta-data html to be placed inside of html head tag
	 * @return string
	 */
	public function Output(){
		//TODO: Implement..
		return "";
	}
}

?>