<?php
namespace Flyf\Models\Abstracts;

use \Flyf\Util\Validate as Validate;

/**
 * The ValueObject is used to store values in. It is
 * attached/used by the models as a value-container.
 *
 * Besides values, it also offers the possibility to
 * define annotations, such as requirements or whether
 * fields should be translatable.
 *
 * The ValueObject is designed to be inherited from,
 * and should not be instantiated on its own. The
 * reason of this is that every model should have its
 * own unique ValueObject, containing its fields as 
 * properties of the ValueObject.
 *
 * @example
 * class \Page\ValueObject extends \Core\ValueObject {
 * 		public $id;
 *		public $title;
 *		public $content;
 * }
 * 
 * @example (of annotation definition)
 * $annotations = array(
 *		'title' => array(
 *			'requirements' => array('length', 10),
 *			'translatable' => true
 * 		);
 * );
 *
 * @author Henrik Haugbølle <hh@signifly.com>
 * @version 2012-01-06
 */
abstract class ValueObject {
	// Annotations is stored in a multi-dimensional, associated array
	// Annotations includes requirements, whether the fields are translatable etc.
	protected $annotations = array();

	/**
	 * Magic setter method of the value object.
	 * Making sure that only existing properties exists and that
	 * the annotations property is never overwritten by mistake.
	 */
	public function __set($key, $value) {
		if (property_exists($this, $key) && $key != 'annotations') {
			$this->$key = $value;
		}
	}

	/**
	 * Magic getter method for the properties.
	 */
	public function __get($key) {
		return $this->$key;
	}

	/**
	 * Method for settings multiple values. The parameter
	 * should be an associative array. Pretty convenient.
	 *
	 * @param array $values (The values to be set)
	 */
	public function SetValues($values) {
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * The evil twin of the SetValues method. Returns
	 * all the values of the ValueObject as an associated
	 * array. The properties of the ValueObject will be
	 * the keys in the array. (The method excludes the 
	 * annotation property).
	 *
	 * @return array (an associative array of keys/values)
	 */
	public function GetValues() {
		$result = array();
		
		foreach (get_object_vars($this) as $key => $value) {
			if ($key != 'annotations') {
				$result[$key] = $value;
			}
		}
		
		return $result;
	}

	/**
	 * This method is a variation of the GetValues method.
	 * it does the same thing, except that it check whether
	 * the values are translatable. If they are not, they
	 * are filtered out of the result.
	 *
	 * @return array (the translatable values)
	 */
	public function GetTranslatableValues() {
		$result = array();
		
		foreach ($this->GetValues() as $key => $value) {
			if (isset($this->annotations[$key]['translatable'])) {
				$result[$key] = $value;
			}
		}

		return $result;
	}

	/**
	 * This method has multiple functions. So the documentation
	 * is in three pieces, as follows:
	 *
	 * If the method is called both parameters (key and value),
	 * the given value is validated against the key's requirements.
	 *
	 * If the method is called with one parameter (key), the current
	 * value in the key's property is validated against the key's requirements.
	 *
	 * If the method is called with no parameteres, all values held in
	 * the value object is validated against their keys respective requirements.
	 *
	 * If a key does not exists in the value object, an exception is thrown.
	 *
	 * If no requirements are defined for a given key, the method will
	 * simply return true.
	 *
	 * @param string $key (optional, the key to validate)
	 * @param string $value (optional, the value to validate against)
	 * @return bool
	 * @throws Exception (if the given key is not a property of the ValueObject)
	 */
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
