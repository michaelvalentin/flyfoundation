<?php
class Flyf_Abstract_Model_ValueObject {
	protected $annotations;
	protected $translatable;

	public function __construct() {
		$this->annotations = array();
		$this->translatable = false;
	}
	
	public function __set($key, $value) {
		if (property_exists($this, $key) && $key != 'annotations' && $key != 'translatable') {
			$this->$key = $value;
		}
	}
	
	public function __get($key) {
		return $this->$key;
	}
	
	public function setValues($values) {
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	
	public function getValues() {
		foreach (get_object_vars($this) as $key => $value) {
			if ($key != 'annotations' && $key != 'translatable') {
				$result[$key] = $value;
			}
		}
		
		return $result;
	}

	// TODO
	public function validate($key = null, $value = null) {
		if ($key != null && $value != null) {
			// validate key against value
		} else if ($key != null) {
			// validate key against current value
		} else {
			// validate all
		}
	}
}
?>
