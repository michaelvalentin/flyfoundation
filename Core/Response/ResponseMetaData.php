<?php
namespace Flyf\Core\Response;

use Flyf\Util\Debug;

use Flyf\Util\Set;

/**
 * Simple class to collect meta-data for a http-response
 * @author MV
 */
class ResponseMetaData {
	private $_keyWords;
	private $_allowedRobots;
	private $_meta;
	
	/**
	 * Create a new response meta data 
	 */
	public function __construct() {
		$this->_keyWords = new Set();
		$this->_allowedRobots = array(
				"INDEX, FOLLOW",
				"NOINDEX, FOLLOW",
				"INDEX, NOFOLLOW",
				"NOINDEX, NOFOLLOW"
				);
		$this->_meta = array();
		$this->setDefaults();
	}
	
	private function SetDefaults(){
		$this->Set("content-type","text/html; charset=UTF-8");
	}
	
	public function __get($name){
		return $this->Get($name);
	}
	
	public function __set($name, $value){
		return $this->Set($name, $value);
	}
	
	public function Set($label,$value){
		$label = strtolower($label);
		$method = "Set".ucfirst($label);
		if(method_exists($this, $method)) $this->$method($value);
		else $this->_meta[$label] = $value;
	}
	
	public function Get($label){
		$label = strtolower($label);
		$method = "Get".ucfirst($label);
		if(method_exists($this, $method))
		{
			return $this->$method();
		}
		else
		{
			return isset($this->_meta[$label]) ? $this->_meta[$label] : null;
		}
	}
	
	/**
	 * Add this keyword to the metadata
	 * 
	 * @param string $keyword
	 */
	public function AddKeyword($keyword){
		$this->_keyWords->Add($keyword);
	}
	
	/**
	 * Add multiple keywords from array
	 * 
	 * @param array $keywords
	 */
	public function AddKeywords(array $keywords){
		foreach($keywords as $keyword){
			$this->AddKeyword($keyword);
		}
	}
	
	/**
	 * Remove this keyword
	 * 
	 * @param string $keyword
	 */
	public function RemoveKeyword($keyword){
		$this->_keyWords->Remove($keyword);
	}
	
	/**
	 * Get all keywords as a comma seperated string
	 * 
	 * @return string
	 */
	public function GetKeywords(){
		return implode(", ",$this->_keyWords->AsArray());
	}
	
	/**
	 * Set all keywords (it's suggested to use the "AddKeyword(s)" methods instead!!!)
	 */
	public function SetKeywords($keywords){
		$words = explode(",",$keywords);
		foreach($words as $l=>$word){
			$this->_keyWords = new Set();
			$this->_keyWords->Add(trim($word));
		}
	}

	/**
	 * Set the robots statement to this string
	 * 
	 * @param string $robotsStatement
	 */
	public function SetRobots($robotsStatement){
		$robotsStatement = strtoupper($robotsStatement);
		if(!in_array($robotsStatement,$this->_allowedRobots)){
			throw new \Flyf\Exceptions\InvalidArgumentException('Robots statement "'.$robotsStatement.'" is not valid.');
		}
		$this->_meta["robots"] = $robotsStatement;
	}

	/**
	 * Set meta description
	 * 
	 * @param string $description
	 */
	public function SetDescription($description) {
		if(strlen($description)>150) Debug::Hint("Descriptions longer than 150 charachters are not shown in Google.");
		if(strlen($description)>260) Debug::Hint("Descriptions longer than 150 charachters are not shown in Google. It's recommended that descriptions doesn't exceed 260 charachters");
		$this->_meta["description"] = $description;
	}
	
	/**
	 * Return meta-data html to be placed inside of html head tag
	 * 
	 * @return string
	 */
	public function HtmlOutput() {
		$output = '';
		
		if (!$this->_keyWords->IsEmpty()) $output .= '<meta name="keywords" content="'.implode(', ', $this->_keyWords->AsArray()).'" />'."\n";
		foreach($this->_meta as $label=>$value){
			if(trim($value)){
				$output.= '<meta name="'.$label.'" content="'.$value.'" />'."\n";
			}
		}

		return $output;
	}
}