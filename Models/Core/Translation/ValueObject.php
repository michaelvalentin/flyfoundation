<?php
namespace Flyf\Models\Core\Translation;

class ValueObject extends \Flyf\Models\Abstracts\SimpleModel\ValueObject {
	public $language;
	protected $languageModel;
	
	public function __construct(\Flyf\Models\Abstracts\RawModel\ValueObject $modelValueObject){
		parent::__construct();
		$this->$excludedFields[] = "languageModel";
		$this->AddAnnotations(array(
				"language" => array(
						"type" => "string",
						"maxLength" => "2",
						"required" => true,
						"reference" => "\\Flyf\\Models\\Core\\Language",
						"reference_column" => "iso",
						"reference_on_update" => "CASCADE",
						"reference_on_delete" => "CASCADE"
						
						//!TODO Add a reference to the Languages...
						)
				));
		$this->languageModel = $modelValueObject;
	}
	
	/**
	 * Get all defined fields with annotations
	 * @return array An array of all fields (keys) with eventual 
	 * annotations as array (values) 
	 */
	public function GetFieldDefinitions() {
		$own = parent::GetFieldDefinitions();
		$modelKey = array();
		foreach($this->languageModel->GetPrimaryKey() as $primaryKey){
			$modelKey[$this->languageModel->
		}
		$translate = array();
		foreach($this->getFieldDefinitions() as $name => $annotation){
			if($annotation["translate"]) $translate[$name] = $annotation;
		}
		return array_merge($own,$modelKey,$translate);
	}
	
	public function GetClassDefinitions() {
		return $this->classAnnotations;
	}

	/**
	 * This method is a variation of the GetValues method.
	 * it does the same thing, except that it check whether
	 * the values are translatable. If they are not, they
	 * are filtered out of the result.
	 *
	 * @return array (the translatable values)
	 */
	public function GetTranslationFields() {
		$result = array();
		
		foreach ($this->GetValues() as $key => $value) {
			if ($this->annotations[$key]['translate']) {
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
		//!!TODO HAve a look at this class, and decide how to deal with it...
		if ($key != null && $value != null) {
			if (!property_exists($this, $key)) {
				throw new \Exception('Property "'.$key.'" of object "'.get_class($this).'" does not exists');
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
				throw new \Exception('Property "'.$key.'" of object "'.get_class($this).'" does not exists');
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

	/**
	 * Add annotations to fields
	 * 
	 * Standard annotations are applied
	 * 
	 * @param array $annotations
	 */
	protected function addAnnotations(array $annotations){
		foreach($annotations as $l=>$a){
			$annotations[$l] = array_merge($this->defaultAnnotations,$a);
		}
		$this->annotations = array_merge($this->annotations, $annotations);
	}
	
	/**
	 * Add annotations for class
	 * 
	 * Standard annotations are applied..
	 * 
	 * @param array $annotations
	 */
	protected function addClassAnnotations(array $annotations){
		foreach($annotations as $l=>$a){
			$annotations[$l] = array_merge($this->defaultClassAnnotations,$a);
		}
		$this->classAnnotations = array_merge($this->classAnnotations, $annotations);
	}
}

?>