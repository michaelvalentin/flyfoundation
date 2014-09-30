<?php
namespace FlyFoundation\Util;

/**
 * Class Set
 *
 * A simple set-structure, based on the hash properties of keys in php-arrays
 *
 * @package Util
 */
class Set implements Collection{
	private $data;
	
	/**
	 * Initiate a new, empty set
	 */
	public function __construct(){
		$this->data = array();
	}
	
	/**
	 * Add an element to the set
	 * @param mixed $element The element to add
	 */
	public function add($element){
        $hash = $this->hash($element);
		$this->data[$hash] = $element;
	}

    public function addAll($elements){
        foreach($elements as $element)
        {
            $this->add($element);
        }
    }
	
	/**
	 * Remove an element from the set
	 * @param mixed $element The element to remove
	 */
	public function remove($element){
        $hash = $this->hash($element);
		if(isset($this->data[$hash])) unset($this->data[$hash]);
	}
	
	/**
	 * Does the set contain this element?
	 * @param mixed $element The element to search for
	 * @return boolean True if the element is contained in the set
	 */
	public function contains($element){
        $hash = $this->hash($element);
		return array_key_exists($hash, $this->data);
	}
	
	/**
	 * @return array: All elements from the set as array
	 */
	public function asArray(){
		return array_values($this->data);
	}

    /**
     * Is this set empty?
     *
     * @return bool
     */
    public function isEmpty(){
		return count($this->data) < 1;
	}

    /**
     * @return int
     */
    public function size()
    {
        return count($this->data);
    }

    public function clear()
    {
        $this->data = array();
    }

    private function hash($value)
    {
        if(is_object($value)){
            return spl_object_hash($value);
        }elseif(is_array($value)){
            return md5(serialize($value));
        }else{
            return md5($value);
        }
    }
}
?>
