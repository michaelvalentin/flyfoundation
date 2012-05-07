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
	private $_description;
	private $_author;
	private $_robots;
	private $_allowedRobots;
	private $_type;
	private $_charset;
	
	/**
	 * Create a new response meta data 
	 */
	function __construct() {
		$this->_keyWords = new Set();
		$this->_allowedRobots = array(
				"INDEX, FOLLOW",
				"NOINDEX, FOLLOW",
				"INDEX, NOFOLLOW",
				"NOINDEX, NOFOLLOW"
				);
		$this->_type = "text/html";
		$this->_charset = "UTF-8";
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
	 * Get an array of all keywords
	 * 
	 * @return array
	 */
	public function GetKeywords(){
		return $this->_keyWords->AsArray();
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
		$this->_robots = $robotsStatement;
	}
	
	/**
	 * Get the current robots statement
	 * 
	 * @return string
	 */
	public function GetRobots(){
		return $this->_robots;
	}

	/**
	 * Set meta description
	 * 
	 * @param string $description
	 */
	public function SetDescription($description) {
		if(strlen($description)>150) Debug::Hint("Descriptions longer than 150 charachters are not shown in Google.");
		if(strlen($description)>260) Debug::Hint("Descriptions longer than 150 charachters are not shown in Google. It's recommended that descriptions doesn't exceed 260 charachters");
		$this->_description = $description;
	}
	
	/**
	 * Get meta description
	 * 
	 * @return string
	 */
	public function GetDescription(){
		return $this->_description;
	}

	/**
	 * Set author in metadata
	 * 
	 * @param string $author
	 */
	public function SetAuthor($author) {
		$this->_author = $author;
	}
	
	/**
	 * Get author in metadata
	 * 
	 * @return string
	 */
	public function GetAuthor() {
		return $this->_author;
	}

	/**
	 * Set type in metadata
	 * 
	 * @param string $type
	 */
	public function SetType($type) {
		$this->_type = $type;
	}
	
	/**
	 * Get type in metadata
	 * 
	 * @return string
	 */
	public function GetType(){
		return $this->_type;
	}

	/**
	 * Set charset in metadata
	 * 
	 * @param string $charset
	 */
	public function SetCharset($charset) {
		$this->_charset = $charset;
	}
	
	/**
	 * Get charset in metadata
	 * 
	 * @return string
	 */
	public function GetCharset(){
		return $this->_charset;
	}
	
	/**
	 * Return meta-data html to be placed inside of html head tag
	 * 
	 * @return string
	 */
	public function HtmlOutput() {
		$output = '';
		if ($this->_type && $this->_charset) $output .= '<meta http-equiv="content-type" content="'.$this->_type.'; charset='.$this->_charset.'" />';
		if ($this->_description) $output .= '<meta name="description" content="'.$this->description.'" />';
		if (count($this->_keyWords->AsArray())) $output .= '<meta name="keywords" content="'.implode(', ', $this->_keyWords->AsArray()).'" />';
		if ($this->_robots) $output .= '<meta name="robots" content="'.$this->_robots.'" />';
		if ($this->_author) $output .= '<meta name="author" content="'.$this->_author.'" />';

		return $output;
	}
}

?>
