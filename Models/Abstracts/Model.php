<?php
namespace Flyf\Models\Abstracts;

use \Flyf\Language\LanguageSettings;

/**
 * The model is an abstract meant to be inherited by all
 * model entities in both the shared part of the application,
 * and the parts unique to each application.
 *
 * The model reflects database-entities and is meant to
 * model "real object". "Real objects" should be understood
 * as object one can find in the real world, like a piece
 * of paper of a pencil, but also mentally constructed objects
 * like associations, assignments etc.
 *
 * The model is able to create new models, load existing models,
 * save newly created models and deleting models. It is also
 * able to keep track of translations and meta data.
 *
 * Please see the documentation for the specific methods to learn
 * more about the workings of the model, and the handling of 
 * translations and meta data.
 *
 * @author Henrik Haugbølle <hh@signifly.com>
 * @version 2011-01-06
 * @dependencies LanguageSettings, DataAccessObject, ValueObject, MetaValueObject
 */
abstract class Model {
	// The DataAccessObject of the model (see the DataAccessObject for documentation). 
	private $dataAccessObject = null;
	// The ValueObject of the model (see the ValueObject for documentation).
	private $valueObject = null;
	// The optional MetaValueObject of the model (see the MetaValueObject for documentation).
	private $metaValueObject = null;

	// The available translations of a model.
	private $translations = array();

	/**
	 * Instead of instantiating a model by calling a constructor,
	 * one should either the Create or Load methods, thus the 
	 * protected constructor.
	 *
	 * If one needs to use a constructor in a inherited model, then
	 * one MUST call the parent constructor by parent::__construct().
	 *
	 * The constructor instantiates a data access object. If a custom
	 * data access object is created for the model, it will be instantiated,
	 * if not the model will use the default data access object.
	 *
	 * It also instantiates a value object. A custom value object should be
	 * created for each model, no default value object is available. If the
	 * value object is not available, the method will throw an exception.
	 *
	 * As default a meta value object is also instantiated. If in a inherited
	 * model one wishes not to use a meta value object, then the method
	 * UseMetaValueObject(false) should be called in the constructor.
	 *
	 * @throws MissingValueObjectException if the value object is missing
	 */
	protected function __construct() {
		
		if (class_exists($valueObjectClass = '\\'.get_class($this).'\\ValueObject')) {
			$this->valueObject = new $valueObjectClass();
			
			$dataAccessObjectClass = '\\'.get_class($this).'\\DataAccessObject';
			$this->dataAccessObject = class_exists($dataAccessObjectClass) ? new $dataAccessObjectClass() : new DataAccessObject();
			
			$this->UseMetaValueObject(true);
		
			$this->dataAccessObject->SetTable($this->GetTable());
			$this->dataAccessObject->SetTableTranslation($this->GetTranslatableTable());
		} else {
			throw new \Flyf\Exceptions\MissingValueObjectException('The value object "'.$valueObjectClass.'" does not exists');
		}
		
		//If in debug, check if the necessary tables exists..
		if(DEBUG){
			if(!$this->dataAccessObject->TableExists()){
				$definitions = $this->getFieldDefinitions();
				$this->dataAccessObject->CreateTable($definitions);
			}	
		}
	}
	
	public function __get($field){
		return $this->Get($field);
	}

	/**
	 * It is possible to turn the meta value object "on" and "off", by
	 * calling this method with a boolean parameter. It should only be
	 * used in the constructor of a model and nowhere else.
	 *
	 * @param bool $use
	 */
	protected function UseMetaValueObject($use = true) {
		if ($use) {
			$metaValueObjectClass = '\\'.get_class($this).'\\MetaValueObject';
			$this->metaValueObject = class_exists($metaValueObjectClass) ? new $metaValueObjectClass() : new MetaValueObject();
		} else {
			$this->metaValueObject = null;
		}

		$this->dataAccessObject->SetFields($this->GetFields());
		$this->dataAccessObject->SetFieldsTranslation($this->GetTranslatableFields());
	}

	/**
	 * The Load method is used to load an existant persistent
	 * instance of a model. This is done either by a giving the
	 * method the id of the model to instantiate, or an associative
	 * array of parameters to be taken into account.
	 *
	 * If no model exists in the database, the method will still
	 * return an "empty" model. Therefore one can check the
	 * existance of a model by calling the Exists() method.
	 *
	 * @example
	 * // To instantiate a model by id a call would look like this
	 * $page = Page::Load(8);
	 *
	 * // To instantiate a model by parameters a call would look like this
	 * $page = Page::Load(array('url' => 'http://someurl', 'title' => 'My title'));
 	 *
	 * @param array $data
	 * @return a model (Page or Blog for example)
	 */
	public static function Load($data) {
		$class = get_called_class();
		$model = new $class();

		$data = $model->dataAccessObject->Load($data);
		
		$model->valueObject->SetValues($data);
		$model->metaValueObject == null ? : $model->metaValueObject->SetValues($data);

		return $model;
	}

	/**
	 * The Create method is used when no persistent instance of
	 * a model exists in advance. One can create a model by calling
	 * the method with an associative array of values to set in
	 * the model. The values will be set directly on the value object
	 * (and meta value object if one exists for the model).
	 *
	 * @example
	 * // To create a new model with a range of values do the following
	 * $page = Page::Create(array(
	 * 		'title' => 'My Title',
	 *		'content' => 'My Content'
	 * );
	 * 
	 * // To make the model persistent one should call the save method
	 * $page->Save();
	 * 
	 * @param array $data
	 * @return a model (Page or Blog for example)
	 */
	public static function Create($data) {
		$class = get_called_class();
		$model = new $class();

		$model->valueObject->SetValues($data);
		$model->metaValueObject == null ? : $model->metaValueObject->SetValues($data);

		return $model;
	}

	/**
	 * The Resource method is a simple Resource factory made for conveinence.
	 * If a custom Resource for the given model exists it will be used, if not
	 * a default Resource will be used instead.
	 *
	 * @return a model Resource (PageResource or Resource for example)
	 */
	public static function Resource() {
		$model = self::Create(array());
		
		$resourceClass = '\\'.get_called_class().'\\Resource';
		$resource = class_exists($resourceClass) ? new $resourceClass($model) : new Resource($model);
		
		return $resource;
	}

	/**
	 * Save updates or inserts a model in the database. Calling
	 * the save method will also save the meta value object if one
	 * exists and the range of available translations.
	 */
	public function Save() {
		$data = $this->metaValueObject != null ? array_merge($this->valueObject->GetValues(), $this->metaValueObject->GetValues()) : $this->valueObject->GetValues();

		$data = $this->dataAccessObject->Save($data);

		$this->valueObject->SetValues($data);
		$this->metaValueObject == null ? : $this->metaValueObject->SetValues($data);

		$this->translations = $this->dataAccessObject->SaveTranslations($this->Get('id'), $this->translations);
	}
	/**
	 * The delete method removes the model from persistent storage
	 * (someone might call this the database). Delete only effects
	 * an object if this object exists (which can be verified by
	 * calling the Exists() method).
	 *
	 * If Delete is called without the id parameter, it will delete
	 * the current model of which is it called on. If a id is given,
	 * it will delete the model with the given id instead.
	 *
	 * The Delete method will also delete all assigned translations.
	 *
	 * @param integer $id
	 */
	public function Delete($id = false) {
		if ($this->Exists()) {
			$this->dataAccessObject->Delete($id ? : $this->Get('id'));
			$this->dataAccessObject->DeleteTranslations($id ? : $this->Get('id'));
		}
	}

	/**
	 * Trash can be thought of as a "soft" delete. A model should
	 * exists to use the Trash method, furthermore a meta value object
	 * needs to be attached to the model to use the Trash method.
	 *
	 * The method does not remove the model from the persistent storage,
	 * but it sets its persistent state as "trashed", which should make
	 * it unavailable for use in a system (other than recovery).
	 *
	 * Like the Delete method, the Trash method can be called with or
	 * without the id parameter. A call without parameter will trash
	 * the model it was called on, calling it with an id will trash
	 * the model with the given id.
	 *
	 * @param integer $id
	 */
	public function Trash($id = false) {
		if ($this->Exists()) {
			if ($this->metaValueObject != null) {
				$data = $this->dataAccessObject->Trash($id ? : $this->Get('id'));

				$this->valueObject->SetValues($data);
				$this->metaValueObject->SetValues($data);
			} else {
				throw new MisingMetaValueObject('The model "'.get_class($this).'" does not have a MetaValueObject attached');
			}
		} else {
			throw new NonExistantModelException('The model "'.get_class($this).'" does not exist.');
		}
	}
	
	/**
	 * The Untrash reverses the effect of a call to the Trash method.
	 * Like for the Delete and Trash method it is required that the
	 * model it is called on exists and that a meta value object is
	 * available.
	 * 
	 * It does only make sense to call the Untrash method on Trashed
	 * models (models on which the Trash method) has been called on.
	 * If Untrash() is called on a non-trashed object, it will have no
	 * influence what so ever.
	 *
	 * Like the Delete method, the Untrash method can be called with or
	 * without the id parameter. A call without parameter will untrash
	 * the model it was called on, calling it with an id will untrash
	 * the model with the given id.
	 *
	 * @param integer $id
	 */
	public function Untrash($id = false) {
		if ($this->Exists()) {
			if ($this->metaValueObject != null) {
				$data = $this->dataAccessObject->Untrash($id ? : $this->get('id'));

				$this->valueObject->setValues($data);
				$this->metaValueObject->setValues($data);
			} else {
				throw new MisingMetaValueObject('The model "'.get_class($this).'" does not have a MetaValueObject attached');
			}
		} else {
			throw new NonExistantModelException('The model "'.get_class($this).'" does not exist.');
		}
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

		$this->LoadTranslation($language);

		if ($language != LanguageSettings::GetCurrentLanguage() && !in_array($key, $this->GetTranslatableFields())) {
			throw new \Exception('It is not allowed to set a non-translatable field, when a language is specified.');
		}

		// First look after a set method. Pass the value and the language to it
		if (method_exists($this, $method)) {
			$this->$method($value, $language);
		} 
		// If language is not default language and if there is an available translation, then set the value in the translation
		elseif ($language != LanguageSettings::GetDefaultLanguage() && in_array($key, $this->GetTranslatableFields())) {
			$this->translations[$language][$key] = $value;
		} 
		// If there is no method, and no translation, then set the value on the valueobject
		else	if (in_array($key, array_keys($this->valueObject->GetValues()))) {
			$this->valueObject->$key = $value;
		} 
		// If the key does not exists in the valueobject, then check if it does in the metavalueobject and set the value there 
		elseif ($this->metaValueObject != null && in_array($key, array_keys($this->metaValueObject->GetValues()))) {
			$this->metaValueObject->$key = $value;
		} 
		else {
			throw new \Flyf\Exceptions\NonExistantPropertyException('The property "'.$key.'" does not exists in class '.get_class($this));
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
		
		$this->LoadTranslation($language);
	
		// First look for at method to match the key. Pass the language to the method to decide translation.
		if (method_exists($this, $method)) {
			return $this->$method($language);
		} 
		// If language is not default language and if there is an available translation, then return that value
		elseif ($language != LanguageSettings::GetDefaultLanguage() && in_array($key, array_keys($this->translations[$language]))) {
			return $this->translations[$language][$key];
		} 
		// If there is no method, and no translation, then return the original value from the valueobject
		elseif (in_array($key, array_keys($this->valueObject->GetValues()))) {
			return $this->valueObject->$key;
		} 
		// If the value is not in the value object, check if there is a metavalueobject and if the key is in there
		elseif ($this->metaValueObject != null && in_array($key, array_keys($this->metaValueObject->GetValues()))) {
			return $this->metaValueObject->$key;
		} 
		else {
			throw new \Flyf\Exceptions\NonExistantPropertyException('The property "'.$key.'" does not exists in class '.get_class($this));
		}
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
		return ($this->get('id')) ? true : false;
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
		return $this->valueObject->Validate();
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
		$values = array_merge($values, $this->valueObject->getValues());
		$values = array_merge($values, $this->metaValueObject != null ? $this->metaValueObject->getValues() : array());
		
		return $values;
	}

	public function AsForm() {
		// TODO
	}

	/**
	 * Returns the table name used in the database by the model. 
	 * 
	 * If a unique/non-conventional table name is used in the
	 * database, the method should be overwritten in the specific
	 * model class.
	 *
	 * @return string (the table)
	 */
	public function GetTable() {
		return preg_replace("/\\\/","_",str_replace("flyf\\models\\","",strtolower(get_class($this))));
	}

	/**
	 * Returns the fields used in the database by the model.
	 
	 * @return array (the fields)
	 */
	public function GetFields() {
		return array_merge(array_keys($this->valueObject->GetValues()), array_keys($this->metaValueObject != null ? $this->metaValueObject->GetValues() : array()));
	}

	/**
	 * Returns the table name used in the database by the model
	 * for translations.
	 *
	 * If a unique/non-conventional table name is used in the
	 * database, the method should be overwritten in the specific
	 * model class.
	 *
	 * @return string (the translation table)
	 */
	public function GetTranslatableTable() {
		return $this->GetTable().'_translation';
	}
	
	/**
	 * Returns the fields used in the database by the model for
	 * translations.
	 *
	 * @return array (the translation fields)
	 */
	public function GetTranslatableFields() {
		return array_keys($this->valueObject->getTranslatableValues());
	}
	
	public function GetFieldDefinitions() {
		if($this->metaValueObject != null){
			return array_merge($this->valueObject->getFieldDefinitions(), $this->metaValueObject->getFieldDefinitions());
		}else{
			return $this->valueObject->getFieldDefinitions();
		}
	}

	/**
	 * Get method for the data access object. Used when
	 * the model class is inherited from and the child
	 * needs access to the data access object.
	 *
	 * @return data access object
	 */
	protected function GetDataAccessObject() {
		return $this->dataAccessObject;
	}
	
	/**
	 * Get method for the value object. Used when
	 * the model class is inherited from and the child
	 * needs access to the value object.
	 *
	 * @return value object
	 */
	protected function GetValueObject() {
		return $this->valueObject;
	}

	/**
	 * Get method for the meta value object. Used when
	 * the model class is inherited from and the child
	 * needs access to the meta value object.
	 *
	 * Be aware that no meta value object might exists.
	 *
	 * @return meta value object
	 */
	protected function GetMetaValueObject() {
		return $this->metaValueObject;
	}

	/**
	 * Used in Set() and Get() methods of the model for lazy
	 * loading of available translations of the model.
	 */
	private function LoadTranslation($language) {
		if ($language != LanguageSettings::GetDefaultLanguage() && !in_array($language, array_keys($this->translations))) {
			$this->translations[$language] = $this->dataAccessObject->LoadTranslation($this->valueObject->id, $language);
		}
	}
}
?>
