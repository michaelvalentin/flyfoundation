<?php
namespace Flyf\Models\Abstracts\RawModel;

use Flyf\Exceptions\InvalidOperationException;

use Flyf\Exceptions\InvalidArgumentException;

use Flyf\Util\Debug;

use \Flyf\Util\Validate as Validate;

/**
 * The ValueObject is an abstract data-structure to represent
 * the structure and nature of the values of a given models.
 * 
 * The ValueObject represents data of the model as also
 * found in the database, and holds all annotations necessary
 * in order to setup the database, validate the current
 * values and other data-related operations.
 *
 * @author Michael Valentin <mv@signifly.com>
 */
abstract class ValueObject {
	private $fieldDefinitions = array(); //array: FieldName => array(<<FieldAnnotations>>)
	private $fieldsSimplified = array(); //array: Simplfied FieldName => FieldName
	private $modelProperties = array(); //array: PropartyName => array(<<ModelPropertyAnnotations>>)
	private $objectData = array();
	private $constructed = false;
	protected $defaultFieldAnnotations = array(
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
			"translate" => false,
			"requireTranslation" => false
		);
	protected $defaultModelPropertyAnnotations = array(
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
		$this->constructed = true;
	}
	public function __set($fieldName, $value) {
		$simplifiedFieldName = $this->simplifyFieldName($fieldName);
		if(!in_array($simplifiedFieldName,array_keys($this->fieldsSimplified))) throw new InvalidOperationException('Trying to set field "'.$fieldName.'" which is not a field in '.get_called_class());
		$this->objectData[$simplifiedFieldName] = $this->toValue($value,$simplifiedFieldName);
	}
	public function __get($fieldName) {
		$simplifiedFieldName = $this->simplifyFieldName($fieldName);
		if(!in_array($simplifiedFieldName,array_keys($this->fieldsSimplified))) throw new InvalidOperationException('Trying to get field "'.$fieldName.'" which is not a field in '.get_called_class());
		$isset = isset($this->objectData[$simplifiedFieldName]);
		if(!$isset) return null;
		return $this->objectData[$simplifiedFieldName];
	}
	
	/**
	 * What is the text representation of the value of this field?
	 * 
	 * @param string $fieldName
	 */
	public function GetAsText($fieldName) {
		return $this->toText($fieldName);
	}

	/**
	 * Set these values on the object
	 *
	 * @param array $values (Array on the format FieldName=>Value) 
	 */
	public function SetValues(array $values) {
		foreach ($values as $fieldName => $value) {
			$this->$fieldName = $value;
		}
	}

	/**
	 * What is the current values of all fields in the object?
	 *
	 * @return array (an array of the format FieldName=>Value)
	 */
	public function GetValues() {
		$result = array();
		
		foreach ($this->fieldsSimplified as $simpleField=>$field) {
			$result[$field] = $this->$simpleField;
		}
		
		return $result;
	}
	
	/**
	 * What is the text-representation of the current values of the object?
	 * 
	 * Text values are outputted in a database friendly way..
	 * 
	 * @return array (an array of format FieldName=>ValueAsText)
	 */
	public function GetTextValues() {
		$result = array();
		
		foreach($this->fieldsSimplified as $simpleField=>$field){
			$result[$field] = $this->toText($simpleField);
		}
		
		return  $result;
	}
	
	/**
	 * What are the definitions of all fields in the model that this 
	 * valueObject represents?
	 * 
	  @return array (An array of all fields in the format FieldName=>Annoatations) 
	 */
	public function GetFieldDefinitions() {
		return $this->fieldDefinitions;
	}
	
	/**
	 * What is the definition of this field?
	 * 
	 * @param string $field (The (potentialy simplified) name of the field to lookup)
	 * @throws InvalidArgumentException (If the field does not exist in this object)
	 * @return array (The annotations for the given field)
	 */
	public function GetFieldDefinition($field){
		$simpleField = $this->simplifyFieldName($field);
		$field = $this->fieldsSimplified[$simpleField];
		if(!in_array($field,array_keys($this->fieldDefinitions))) throw new InvalidArgumentException('The field "'.$field.'" does not exist in "'.get_called_class().'"');
		return $this->fieldDefinitions[$field];
	}
	
	/**
	 * What is the definition of the model that this valueobejct
	 * represents?
	 * 
	 * @return array (Properties of the model in the format PropertyName=>Annotations)
	 */
	public function GetModelProperties() {
		return $this->modelProperties;
	}

	/**
	 * What are the definitions of the translatable fields in 
	 * this valueobject?
	 *
	 * @return array (the definition of all Translatable fields)
	 */
	public function GetTranslatableFieldsDefinitions() {
		$result = array();
		
		foreach ($this->fieldDefinitions as $fieldName => $options) {
			if ($options['translate']) {
				$result[$fieldName] = $options;
			}
		}

		return $result;
	}
	
	/**
	 * What fields are translated in this object?
	 * 
	 * @return array (An array of names of the transalted fields in this object)
	 */
	public function GetTranslatableFieldsNames(){
		return array_keys($this->GetTranslatableFieldsDefinitions());
	}
	
	/**
	 * Is any fields in this object translated?
	 * 
	 * @return bool (True if any fields are translated, otherwise false)
	 */
	public function HasTranslatableFields(){
		return count($this->GetTranslatableFieldsDefinitions()) > 0;
	}
	
	/**
	 * What is the PrimaryKey of this object?
	 * 
	 * Returns the PrimaryKey as an associative array with field name(s)
	 * as the keys and eventual key-value(s) as the values.
	 * 
	 * @return array (Array of format FieldName=>Value) 
	 */
	public function GetPrimaryKey() {
		$primaryKey = array();
		foreach($this->fieldDefinitions as $fieldName => $annotations){
			if($annotations["primaryKey"]) $primaryKey[$fieldName] = $this->$fieldName;
		}
		return $primaryKey;
	}
	
	/**
	 * What is the definition for the primary key fields? 
	 * 
	 * Returns array of format (FieldName=>Annotations)
	 * 
	 * @return array (The primary key definition as an array FieldName=>Annotations)
	 */
	public function GetPrimaryKeyDefinition() {
		$primaryKey = array();
		foreach($this->fieldDefinitions as $fieldName => $annotations){
			if($annotations["primaryKey"]) $primaryKey[$fieldName] = $annotations;
		}
		return $primaryKey;
	}

	/**
	 * What is the best legal value based on this input for this field?
	 * 
	 * @param mixed $input (The input to <try> and understand)
	 * @param string $fieldName (The (potentially simplified) name of the field to interpret for)
	 * @return mixed (A legal value for the field, based on the input (if in any acceptable way possible) otherwise null)
	 */
	protected function toValue($input,$fieldName){
		$options = $this->GetFieldDefinition($fieldName);
		switch(strtolower($options["type"])){
			case "datetime" :
			case "date" :
				$value = is_a($input,"\\DateTime") ? $input : new \DateTime($input);
				break;
			default :
				$value = $input;
				break;
		}
		return $value;
	}
	
	/**
	 * What is the text representation for the current value of this field?
	 * 
	 * Output is as database friendly as possible.. :-)
	 * 
	 * @param string $fieldName (the (potentialy simplified) field name of the value to output as text)
	 */
	protected function toText($fieldName) {
		$options = $this->GetFieldDefinition($fieldName);
		$value = $this->$fieldName;
		switch(strtolower($options["type"])){
			case "datetime" :
				$text = is_a($value, "\\DateTime") ? $value->format("Y-m-d H:i:s") : $value;
				break;
			case "date" :
				$text = is_a($value, "\\DateTime") ? $value->format("Y-m-d") : $value;
			default :
				if(!$value)
				{
					$text = $value;
				}
				else
				{
					$text = "".$value;
				}
				break;
		}
		return $text;
	}
	
	/**
	 * What is the simplified version of the given field name?
	 * 
	 * @param string $fieldName (the field name to simplify)
	 * @return string (the simplified field name)
	 */
	protected function simplifyFieldName($fieldName){
		$fieldName = str_replace("_","",$fieldName);
		$fieldName = strtolower($fieldName);
		return $fieldName;
	}
	
	/**
	 * Add these fields to the value object
	 * 
	 * Fields are added with key as name and value as annotations
	 * 
	 * @param array $fields
	 */
	protected function addFields(array $fields){
		foreach($fields as $name=>$annotations){
			$this->addField($name,$annotations);
		}
	}
	
	/**
	 * Add this field to the value object with the given annotations
	 * 
	 * @param string $name
	 * @param mixed $annotations
	 */
	protected function addField($name, $annotations){
		$this->ensureNotConstructed();
		
		//Add the simplified version for easy look-up
		$simplifiedName = $this->simplifyFieldName($name);
		if(!isset($this->fieldsSimplified[$simplifiedName]))
		{
			$this->fieldsSimplified[$simplifiedName] = $name;
		}
		else
		{
			//If name is allready taken, we must throw an exception
			throw new InvalidOperationException('A field with a name similair to "'.$name.'" allready exists in '.get_called_class());			
		}
		
		if(!is_array($annotations)) $annotations = array();
		$annotations = array_merge($this->defaultFieldAnnotations,$annotations);
		$this->fieldDefinitions[$name] = $annotations;
		
		//Check for unknown annotations in debug mode and hint if found any..
		/*if(DEBUG)
		{
			foreach($annotations as $label=>$option){
				if(!in_array($label,array_keys($this->defaultFieldAnnotations)))
				{
					Debug::Hint('Use of unknown annotation "'.$label.'" in "'.get_called_class().'"');
				}
			}
		}*/
	}
	
	/**
	 * Add these properties for the model (denoted as an array of arrays of annotations)
	 * 
	 * @param array $properties (An array of arrays of annotations)
	 */
	protected function addModelProperties(array $properties){
		foreach($properties as $annotations){
			if(!is_array($annotations)) throw new InvalidArgumentException("Annotations for a ModelProperty must be an array - a property with no annotations doesn't really make sense...");
			$this->addModelProperty($annotations);
		}
	}
	
	/**
	 * Add a property for the model
	 * 
	 * @param array $annotations
	 */
	protected function addModelProperty(array $annotations){
		$this->ensureNotConstructed();
		$annotations = array_merge($this->defaultModelPropertyAnnotations, $annotations);
		$this->modelProperties[] = $annotations;
		
		//If in debug, hint if theres is an unknown annotation (useful for debug!)
		if(DEBUG)
		{
			foreach($annotations as $label=>$option){
				if(!in_array($label,array_keys($this->defaultModelPropertyAnnotations)))
				{
					Debug::Hint('Use of unknown modelAnnotation "'.$label.'" in "'.get_called_class().'"');
				}
			}
		}
	}

	/**
	 * Ensure that the object has not finished constructing
	 * 
	 * @throws InvalidOperationException (if ValueObject is allready constructed)
	 */
	protected function ensureNotConstructed(){
		if($this->constructed) throw new InvalidOperationException("It is not allowed to perform this operation after the ValueObject has been created.");
	}
	
	/**
	 * Ensure that the object has finished constructing
	 * 
	 * @throws InvalidOperationException
	 */
	protected function ensureConstructed(){
		if(!$this->constructed){
			Debug::Hint("You might have forgot to call the parent constructor in ".get_called_class());
			throw new InvalidOperationException("It is not allowed to perform this operation before the ValueObject has been created.");
		}
	}
}
?>
