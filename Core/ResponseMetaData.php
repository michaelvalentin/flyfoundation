<?php
namespace Flyf\Core;

#require_once __DIR__.'/../util/Set.php';
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
	private $_type;
	private $_charset;
	
	/**
	 * Create a new response meta data 
	 */
	function __construct() {
		$this->_keyWords = new Set();
		$this->_robots = new Set();
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
		$this->_keyWords->Remove($keyword);
	}

	/**
	 * Add this robot to the metadata
	 * @param string $robot
	 */
	public function AddRobot($robot){
		$this->_robots->Add($robot);
	}
	
	/**
	 * Add multiple robots from array
	 * @param array $robots
	 */
	public function AddRobots(array $robots){
		foreach($robots as $robot){
			$this->AddRobot($robot);
		}
	}
	
	/**
	 * Remove this robot
	 * @param string $robot
	 */
	public function RemoveRobot($robot){
		$this->_robots->Remove($robot);
	}

	/**
	 *	Set meta description
	 * @param string $description
	 */
	public function SetDescription($description) {
		$this->_description = $description;
	}

	/**
	 *	Set author in metadata
	 * @param string $author
	 */
	public function SetAuthor($author) {
		$this->_author = $author;
	}

	/**
	 *	Set type in metadata
	 * @param string $author
	 */
	public function SetType($type) {
		$this->_type = $type;
	}

	/**
	 *	Set charset in metadata
	 * @param string $charset
	 */
	public function SetCharset($charset) {
		$this->_charset = $charset;
	}
	
	/**
	 * Return meta-data html to be placed inside of html head tag
	 * @return string
	 */
	public function Output() {
		$output = '';
		if ($this->_type && $this->_charset) $output .= '<meta http-equiv="content-type" content="'.$this->_type.'; '.$this->_charset.'" />';
		if ($this->_description) $output .= '<meta name="description" content="'.$this->description.'" />';
		if (count($this->_keyWords->AsArray())) $output .= '<meta name="keywords" content="'.implode(', ', $this->_keyWords->AsArray()).'" />';
		if (count($this->_robots->AsArray())) $output .= '<meta name="robots" content="'.implode(', ', $this->_robots->AsArray()).'" />';
		if ($this->_author) $output .= '<meta name="author" content="'.$this->author.'" />';

		return $output;
	}
}

?>
