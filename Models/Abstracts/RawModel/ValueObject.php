<?php
namespace Flyf\Models\Abstracts\RawModel;

use Flyf\Util\Debug;

use \Flyf\Util\Validate as Validate;

/**
 * The ValueObject is an abstract data-structure to represent
 * the structure and nature of the values of a given models.
 * 
 * The ValueObject represents that data of the model as also
 * found in the database, and holds all annotations necessary
 * in order to setup the database, validate the current
 * values and other data-related operations.
 *
 * @author Michael Valentin <mv@signifly.com>
 */
abstract class ValueObject {
	// Annotations is stored in a multi-dimensional, associated array
	// Annotations includes requirements, whether the fields are translatable etc.
	protected $annotations = array();
	protected $modelAnnotations = array();
	protected $excludedFields = array('excludedFields','annotations','modelAnnotations','defaultAnnotations','defaultModelAnnotations');
	protected $defaultAnnotations = array(
			"type" => "VARCHAR",
			"maxLength" => false,
			"primaryKey" => false,
			"required" => false,
			"autoIncrement" => false,
			"default" => false,
			"unique" => false,
			"reference" => false,
			"reference_column" => false,
			"reference_match" => false,
			"reference_on_delete" => "NO ACTION",
			"reference_on_update" => "NO ACTION",
			"reference_load" => "LAZY",
			"patterns" => false,
			"unsigned" => false,
			"translate" => false
		);
	protected $defaultModelAnnotations = array(
			"name" => false,
			"constraint" => false,
			"index" => false,
			"type" => false,
			"fulltext" => false,
			"reference_table" => false,
			"reference_columns" => array(),
			"reference_match" => false,
			"reference_on_delete" => "NO ACTION",
			"reference_on_update" => "NO ACTION",
			"columns" => array()		
		);

	public function __construct(){
		//Empty constructor for consistency..
	}
	public function __set($key, $value) {
		if (property_exists($this, $key) && !in_array($key,$this->excludedFields)) {
			$this->$key = $value;
		}
	}
	public function __get($key) {
		return $this->$key;
	}

	/**
	 * Set these values
	 *
	 * @param array $values The values to be set, as an associated array with fields names as keys 
	 */
	public function SetValues($values) {
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * What is the current values of the object?
	 *
	 * @return array (an associative array of keys/values)
	 */
	public function GetValues() {
		$result = array();
		
		foreach (get_object_vars($this) as $key => $value) {
			if (!in_array($key,$this->excludedFields)) {
				$result[$key] = $value;
			}
		}
		
		return $result;
	}
	
	/**
	 * What are the definitions of all fields in the model that this 
	 * valueObject represents?
	 * 
	 * @return array An array of all fields (keys) with eventual annotations as array (values) 
	 */
	public function GetFieldDefinitions() {
		$result = array();
		foreach(get_object_vars($this) as $key => $value){
			if(!in_array($key, $this->excludedFields)){
				if(isset($this->annotations[$key])){
					$result[$key] = $this->annotations[$key];
				}else{
					$result[$key] = $this->defaultAnnotations;
				}
			}
		}
		return $result;
	}
	
	/**
	 * What is the definition of the model that this valueobejct
	 * represents?
	 */
	public function GetModelDefinition() {
		return $this->modelAnnotations;
	}

	/**
	 * What are the definitions of the translated fields in the
	 * model that this valueobject represents?
	 *
	 * @return array (the definition of all Translatable fields)
	 */
	public function GetTranslationFieldsDefinitions() {
		$result = array();
		
		foreach ($this->GetValues() as $column => $options) {
			if ($this->annotations[$column]['translate']) {
				$result[$column] = $options;
			}
		}

		return $result;
	}
	
	/**
	 * What is the PrimaryKey of this object?
	 * 
	 * Returns the PrimaryKey as an associative array with key column(s)
	 * as the keys and eventual key-value(s) as the values.
	 * 
	 * @return array Associative array with columns as keys and values as values 
	 */
	public function GetPrimaryKey() {
		$primaryKey = array();
		foreach($this->GetFieldDefinitions() as $name => $annotation){
			if($annotation["primaryKey"]) $primaryKey[$name] = $this->__get($name);
		}
		return $primaryKey;
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
		
		//Check for unknown annotations in debug mode and hint if found any..
		if(DEBUG)
		{
			foreach($annotations as $column=>$options){
				foreach($options as $label=>$option){
					if(!in_array($label,array_keys($this->defaultAnnotations)))
					{
						Debug::Hint('Use of unknown annotation "'.$label.'" in "'.get_called_class().'"');
					}
				}
			}
		}
	}
	
	/**
	 * Add annotations for class
	 * 
	 * Standard annotations are applied..
	 * 
	 * @param array $annotations
	 */
	protected function addModelAnnotations(array $annotations){
		if(DEBUG)
		{
			foreach($annotations as $label=>$option){
				if(!in_array($label,array_keys($this->defaultModelAnnotations)))
				{
					Debug::Hint('Use of unknown modelAnnotation "'.$label.'" in "'.get_called_class().'"');
				}
			}
		}
		foreach($annotations as $l=>$a){
			$annotations[$l] = array_merge($this->defaultModelAnnotations,$a);
		}
		$this->modelAnnotations = array_merge($this->modelAnnotations, $annotations);
	}
}
?>
