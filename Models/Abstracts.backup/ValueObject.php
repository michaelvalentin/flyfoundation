<?php
namespace Flyf\Models\Abstracts;

use \Flyf\Util\Validate as Validate;

abstract class ValueObject {
	protected $annotations = array();

	public function __set($key, $value) {
		if (property_exists($this, $key) && $key != 'annotations' && $key != 'translatable') {
			$this->$key = $value;
		}
	}
	
	public function __get($key) {
		return $this->$key;
	}
	
	public function SetValues($values) {
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	
	public function GetValues() {
		$result = array();
		
		foreach (get_object_vars($this) as $key => $value) {
			if ($key != 'annotations' && $key != 'translatable') {
				$result[$key] = $value;
			}
		}
		
		return $result;
	}

	public function GetTranslatableValues() {
		$result = array();
		
		foreach ($this->GetValues() as $key => $value) {
			if (isset($this->annotations[$key]['translatable'])) {
				$result[$key] = $value;
			}
		}

		return $result;
	}

	// TODO
	public function Validate($key = null, $value = null) {
		if ($key != null && $value != null) {
			if (!property_exists($this, $key)) {
				throw new Exception('Property "'.$key.'" of object "'.get_class($this).'" does not exists');
			}

			if (isset($this->annotations[$key]['requirements'])) {
				foreach ($this->annotations[$key]['requirements'] as $method => $argument) {
					if (Validate::Validate($method, $value, $argument) === false) {
						return false;
					}
				}
			}

			return true;
		} else if ($key != null) {
			if (!property_exists($this, $key)) {
				throw new Exception('Property "'.$key.'" of object "'.get_class($this).'" does not exists');
			}
			
			return $this->Validate($key, $this->$key);
		} else {
			foreach ($this->GetValues() as $key => $value) {
				if ($this->Validate($key, $value) === false) {
					return false;
				}
			}

			return true;
		}
	}
}
?>
