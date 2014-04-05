<?php
namespace Util;

/**
 * Class Set
 *
 * A simple set-structure, based on the hash properties of keys in php-arrays
 *
 * @package Util
 */
class Set {
	private $_data;
	
	/**
	 * Initiate a new, empty set
	 */
	public function __construct(){
		$this->_data = array(); 
	}
	
	/**
	 * Add an element to the set
	 * @param mixed $element The element to add
	 */
	public function Add($element){
		$this->_data[$element] = true;
	}
	
	/**
	 * Remove an element from the set
	 * @param mixed $element The element to remove
	 */
	public function Remove($element){
		if(isset($this->_data[$element])) unset($this->_data[$element]);
	}
	
	/**
	 * Does the set contain this element?
	 * @param mixed $element The element to search for
	 * @return boolean True if the element is contained in the set
	 */
	public function Contains($element){
		return array_key_exists($element, $this->_data);
	}
	
	/**
	 * @return array: All elements from the set as array
	 */
	public function AsArray(){
		return array_keys($this->_data);
	}

    /**
     * Is this set empty?
     *
     * @return bool
     */
    public function IsEmpty(){
		return count($this->_data) < 1;
	}
}
?>