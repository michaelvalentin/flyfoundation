<?php
namespace Flyf\Util;

class HtmlString {
	private $content;
	private $allowedTags;
	
	/**
	 * Construct a new piece of html
	 * @param string $content The html to use
	 * @param array $allowedTags The tags allowed to use in the content. If first tag is "all" all tags are allowed, and html string is not post porcessed.
	 */
	public function __construct($content, array $allowedTags = array()){
		$this->content = $content;
	}
	
	/**
	 * Check wether the html content is valid (eg. tags are valid and all tags are properly nested and closed)
	 * @return boolean True if the content is valid html
	 */
	public function Validate(){
		if(!DEBUG) return true; //Disable out of debug for performance..
		return true; //TODO: Implement.. :-)
	}
	
	
	public function Output(){
		if(isset($this->allowedTags[0]) && $this->allowedTags[0] == "all"){
			return $this->content;
		}else{
			return $this->content; //TODO: Implement with exclusion of not allowed tags...
		}
	}
}

?>