<?php
namespace Flyf\Models\Abstracts;

use Flyf\Exceptions\InvalidArgumentException;

use Flyf\Models\Core\Language;
use Flyf\Util\Debug;
use Flyf\Exceptions\ModelException;
use Flyf\Exceptions\InvalidModelException;
use Flyf\Exceptions\UnexpectedStateException;
use Flyf\Language\LanguageSettings;
use Flyf\Models\Core\Translation;
use Flyf\Database\TableBuilder;

/**
 * Abstract models are inteded to be extended and turned
 * into data-models that handles business-data that is meant
 * to be saved to the database.
 * 
 * The RawModel is the simplest type of model, which has no
 * fields defined and allows for a completely custom type of
 * model.
 * 
 * In most cases it is better to use SimpleModel, which enforces
 * an integer id (Surrogate Primary Key) which is in many cases
 * the best way to implement data entities.
 *
 * @author Michael Valentin <mv@signifly.com>
 */
abstract class RawModel {
	protected $dataAccessObject = null; // The DataAccessObject of the model (see the DataAccessObject for documentation). 
	protected $valueObject = null;	// The ValueObject of the model (see the ValueObject for documentation).
	protected $translations = array(); // The loaded translations of a model.
	protected $resource = null; // The resource object for this model.

	protected function __construct() {
		$var = get_called_class();
		$this->valueObject = $this->loadValueObject();
		$this->dataAccessObject = $this->loadDataAccessObject();
		if(DEBUG)
		{
			$tableExists = \Flyf\Database\SimpleQueries::TableExists($this->GetTable());
			if(!$tableExists)
			{ 
				TableBuilder::BuildFromModel($this);	
			}
			else
			{
				//TODO Implement table update features...
				Debug::Hint("Automatic update of tables is not implemented yet, so you must delete table and rebuild when changing the Fields- or ModelDefinition");
			}
		}
	}
	public function __get($field){
		return $this->Get($field);
	}
	public function __set($field,$value){
		$this->Set($field,$value);
	}
	
	/**
	 * Load The ValueObject for this class
	 * 
	 * Searches through parent implementations until a suitable ValueObject is found
	 */
	protected function loadValueObject(){
		$valueObject = null;
		$class = get_called_class();
		//Step backward the inheritance-chain until a DataAccessObject is found...
		while($valueObject==null && $class){
			$voClass = '\\'.$class.'\\ValueObject';
			if(class_exists($voClass))
			{ 
				$valueObject = new $voClass(); 
			}
			else
			{
				$class = get_parent_class($class);
			}
		}
		return $valueObject;
	}
	
	/**
	 * Get the ValueObject for this model
	 * 
	 * @return \Flyf\Models\Abstracts\RawModel\ValueObject
	 */
	protected function GetValueObject(){
		return $this->valueObject;
	}
	
	/**
	 * Load The DataAccessObject (DAO) for this class
	 *
	 * Searches through parent implementations until a suitable DAO is found
	 */
	protected function loadDataAccessObject(){
		$dataAccessObject = null;
		$class = get_called_class();
		//Step backward the inheritance-chain until a DataAccessObject is found...
		while($dataAccessObject==null && $class){
			$daoClass = '\\'.$class.'\\DataAccessObject';
			if(class_exists($daoClass)) $dataAccessObject = new $daoClass();
			$class = get_parent_class($class);
		}
		if($dataAccessObject == null) throw new UnexpectedStateException("It should not be possible that no DataAccessObject is found, when inheritting from RawModel, as the RawModel DOES HAVE a DataAccessObject.");
		$dataAccessObject->SetTable($this->GetTable());
		$dataAccessObject->SetPrimaryKey(array_keys($this->valueObject->GetPrimaryKey()));
		$dataAccessObject->SetFields($this->valueObject->GetFieldDefinitions());
		$dataAccessObject->SetModel($this->valueObject->GetModelProperties());
		return $dataAccessObject;
	}

	/**
	 * Get the DAO of this model
	 * 
	 * @return \Flyf\Models\Abstracts\RawModel\DataAccessObject
	 */
	protected function GetDataAccessObject(){
		return $this->dataAccessObject;
	}

	protected function loadResource(){
		$class = get_called_class();
		$model = new $class();
		$resource = null;
		//Go back the inheritance-chain to find a Resource object..
		while($resource == null && $class){
			$resourceClass = '\\'.$class.'\\Resource';
			if(class_exists($resourceClass)) $resource = new $resourceClass($model);
		}
		if($resource == null) throw new UnexpectedStateException("There should always be a resource object; if nothing else, then the resource object from the RawModel..");
		
		return $resource;
	}
	
	public function GetResource(){
		if($this->resource === null) $this->resource = $this->loadResource();
		return $this->resource;
	}
	
	/**
	 * What should the name for this models database table be?
	 */
	public function GetTable(){
		$tablename = strtolower(get_called_class());
		$tablename = str_replace("flyf\\","",$tablename);
		$tablename = str_replace("models\\","",$tablename);
		$tablename = str_replace("\\","_",$tablename);
		return \Flyf\Core\Config::GetValue("database_table_prefix").$tablename;
	}
	
	
	/**
	 * Save updates or inserts a model in the database. Calling
	 * the save method will also save the range of available translations.
	 */
	public function Save() {
		if(!$this->Valid()) throw new InvalidModelException("It is not allowed to save an invalid model. Please make all fields valid before saving.");
		$data = $this->valueObject->GetTextValues();
		$data = $this->dataAccessObject->Save($data,!$this->Exists());
		if(!$this->HasField("id")) unset($data["id"]);
		$this->valueObject->SetValues($data);
		foreach($this->translations as $translation){
			foreach($this->valueObject->GetPrimaryKey() as $column=>$value){
				$fieldname = "model_".$column;
				$translation->$fieldname = $value;
			}
			$translation->Save();
		}
	}
	
	/**
	 * The delete method removes the model from the database. 
	 * Delete only effects an object if this object exists 
	 * (which can be verified by calling the Exists() method).
	 *
	 * The Delete method will also delete all assigned translations.
	 */
	public function DeleteThis() {
		if ($this->Exists()) {
			$this->dataAccessObject->Delete($this->valueObject->GetValues());
			foreach($this->translations as $translation){
				$translation->DeleteThis();
			}
		}
	}
	
	public function HasField($name){
		return in_array($name,array_keys($this->valueObject->GetFieldDefinitions()));
	}
	
	/**
	 * Tells whether the model exists in the persitent storage.
	 * 
	 * @example
	 * $page = Page::Create(array('title' => 'My Title'));
	 * $page->Exists(); // will return false
	 *
	 * $page->Save();
	 * $page->Exists(); // Will return true
	 *
	 * @return bool (whether the model exists or not)
	 */
	public function Exists() {
		return $this->dataAccessObject->Exists($this->valueObject->GetPrimaryKey());
	}
	
	public function GetEmptyValueObject() {
		return $this->loadValueObject();
	}
	
	/**
	 * The global Set method of the model. This method should
	 * be used whenever one needs to change or set a value
	 * on a model.
	 *
	 * The method takes two required and one optional parameter.
	 * The two required are the key and the value, the third optional
	 * is the language of which one is setting the value (is only
	 * relevant when dealing with translations).
	 *
	 * The method will look if a custom method with the convention
	 * "SetKeyWithUpperCase" is defined and pass the value and
	 * language if it exists.
	 *
	 * If a custom method does not exists, it checks whether a 
	 * language is set (and is not the default language) and whether
	 * the key is translatable. If the key exists in the translations
	 * it sets it in the translations array.
	 *
	 * If the key is not translatable, it check whether the key
	 * exists in the value object and sets the value on the value object
	 * if it exists.
	 * 
	 * If the key does not exists in the value object, it checks whether
	 * it exists in the meta value object (if one is available).
	 *
	 * If the key does not exists in either of the above, it throws
	 * an exception saying so.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param string $language (optional)
	 * @throws NonExistantPropertyException (thrown if the given key does not exists in the model)
	 */
	public function Set($key, $value, $language = null) {
		$language = $language ? : LanguageSettings::GetCurrentLanguage();
		$method = 'Set'.str_replace(' ', '', ucfirst(str_replace('_', ' ', $key)));

		// First look after a set method. Pass the value and the language to it
		if (method_exists($this, $method)) {
			$this->$method($value, $language);
		}
		
		// If there is no method and the value is not defined in the value object, we throw an exception..
		elseif(!in_array($key,array_keys($this->valueObject->GetValues()))){
			throw new \Flyf\Exceptions\NonExistantPropertyException('The property "'.$key.'" does not exists in class '.get_called_class());			
		}
		
		// If we are in default langauge or the field is not translated, we just write to the value object
		elseif($language == LanguageSettings::GetDefaultLanguage() || !in_array($key, $this->valueObject->GetTranslatableFieldsNames())){
			$this->valueObject->$key = $value;
		}
		
		// If language is not default language and field is translated (which is given by statement one above), we add the value to translations
		else {
			$this->loadTranslation($language);
			$this->translations[$language]->$key = $value;
		} 
	}

	/**
	 * The global Get method of the model. This method should
	 * be used whenever one needs to access a value of the model.
	 *
	 * The method takes one required parameter and one optional
	 * parameter. The first being the key of the value to fetch
	 * the second being a language parameter.
	 *
	 * The method will look if a custom method with the convention
	 * "GetKeyWithUpperCase" is defined and pass the value and
	 * language if it exists.
	 *
	 * If a custom method does not exists, it checks whether a 
	 * language is set (and is not the default language) and whether
	 * the key is translatable. If the key exists in the translations
	 * it gets the value from the translations array.
	 *
	 * If the key is not translatable, it check whether the key
	 * exists in the value object and gets the value from the value object.
	 * 
	 * If the key does not exists in the value object, it checks whether
	 * it exists in the meta value object (if one is available).
	 *
	 * If the key does not exists in either of the above, it throws
	 * an exception saying so.
	 * 
	 * @param string $key
	 * @param string $language (optional)
	 * @throws NonExistantPropertyException (thrown if the given key does not exists in the model)
	 */
	public function Get($key, $language = null) {
		$language = $language ? : LanguageSettings::GetCurrentLanguage();
		$method = 'Get'.str_replace(' ', '', ucfirst(str_replace('_', ' ', $key)));
	
		// First look for at method to match the key. Pass the language to the method to decide translation.
		if (method_exists($this, $method)) {
			return $this->$method($language);
		}

		// If there is no method and the key is not in the value object we throw an exception
		if(!in_array($key,array_keys($this->valueObject->GetValues()))){
			throw new \Flyf\Exceptions\NonExistantPropertyException('The property "'.$key.'" does not exists in class '.get_class($this));
		}
		
		// If language is default or the field is not translated, return from the value object
		if($language == LanguageSettings::GetDefaultLanguage() || ! in_array($key,$this->valueObject->GetTranslatableFieldsNames())){
			return $this->valueObject->$key;
		}
		
		// If language is not default language and if there is an available translation, then return that value
		$this->loadTranslation($language);
		//!!TODO Make sure that the valueObject returns null if value haven't been set..
		if($this->translations[$language]->$key !== null || $this->translations[$language]->$key === ""){
			return $this->translations[$language]->$key;
		} 
		
		// If we don't have a translation, we return the default language..
		return $this->valueObject->$key;
	}

	/**
	 * Check whether the models state is valid or invalid.
	 * Uses the requirements in the annotations of the valueobject
	 * to validate the properties (see the ValueObject for more 
	 * documentation).
	 *
	 * @return bool (whether the state of the model is valid or invalid)
	 */
	public function Valid() {
		return \Flyf\Util\Validate::Model($this);
	}

	/**
	 * Flattens a model object into an associated array. The 
	 * values are extracted from the value object and from the
	 * meta value object.
	 *
	 * The method is meant for overwriting, but one should always
	 * call the parent method for initial values when overriding.
	 * 
	 * @return array (the model flattened as an array)
	 */
	public function AsArray() {
		$values = array('model' => strtolower(get_class($this)));
		$values = array_merge($values, $this->valueObject->getValues()); //!!TODO: We should rather implement a "GetValues" method on model.
		
		return $values;
	}

	/**
	 * Load this translation for this language
	 */
	private function loadTranslation($language) {
		if(!count($this->valueObject->GetTranslatableFieldsDefinitions())) return;
		if (!isset($this->translations[$language])) {
			$translationModel = new Translation($this);
			$languageData  = array_merge(array("language_iso"=>$language),$this->valueObject->GetPrimaryKey());
			$result = $translationModel->LoadModel($languageData);
			if(!$result){
				$result = $translationModel->CreateModel($languageData);
			} 
			$this->translations[$language] = $result;
		}
	}
	
	/**
	 * Load a model that matches this data
	 * 
	 * Load the first instance of this model from the database
	 * that matches the given data. Persistent results can only
	 * be made when PrimaryKey or another Unique key is used as
	 * data.
	 * 
	 * If no model can be found with the given crieterions the
	 * method returns false.
	 * 
	 * @param array $data
	 * @return \Flyf\Models\Abstracts\RawModel A model that match
	 */
	public static function Load($data) {
		if(!is_array($data)) throw new InvalidArgumentException("Load on a RawModel must be perfromed with an array of data.");
		$model = static::Create();
		
		$result = $model->dataAccessObject->Load($data);
		
		if(!$result) return false;
		
		$model->valueObject->SetValues($result);

		return $model;
	}

	/**
	 * Create a model initialized with this data
	 * 
	 * @param array $data The data to initialize the model with
	 * @return \Flyf\Models\Abstracts\RawModel A new model
	 */
	public static function Create(array $data = array()) {
		$class = get_called_class();
		$model = new $class();

		$model->valueObject->SetValues($data);

		return $model;
	}

	/**
	 * Delete model from the database, based on it's primary key
	 * 
	 * @param array $primaryKey
	 */
	public static function Delete(array $primaryKey) {
		static::DataAccessObject()->Delete($primaryKey);
	}
	
	/**
	 * The Resource method is a simple Resource factory made for conveinence.
	 * If a custom Resource for the given model exists it will be used, if not
	 * a default Resource will be used instead.
	 *
	 * @return a model Resource (PageResource or Resource for example)
	 */
	public static function Resource() {
		$model = static::Create();
		return $model->GetResource();
	}
}