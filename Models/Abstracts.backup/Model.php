<?php
namespace Flyf\Models\Abstracts;

use \Flyf\Language\LanguageSettings as LanguageSettings;

abstract class Model {
	private $dataAccessObject = null;
	private $valueObject = null;
	private $metaValueObject = null;

	private $translations = array();

	protected function __construct() {
		if (class_exists($valueObjectClass = '\\'.get_class($this).'\\ValueObject')) {
			$this->valueObject = new $valueObjectClass();
			
			$dataAccessObjectClass = '\\'.get_class($this).'\\DataAccessObject';
			$this->dataAccessObject = class_exists($dataAccessObjectClass) ? new $dataAccessObjectClass() : new DataAccessObject();
		
			$this->UseMetaValueObject(true);
		
			$this->dataAccessObject->SetTable($this->GetTable());
			$this->dataAccessObject->SetFields($this->GetFields());
			
			$this->dataAccessObject->SetTableTranslation($this->GetTranslatableTable());
			$this->dataAccessObject->SetFieldsTranslation($this->GetTranslatableFields());
		} else {
			throw new \Flyf\Exceptions\MissingValueObjectException('The value object "'.$valueObjectClass.'" does not exists');
		}
	}

	public function GetTable() {
		return strtolower(end(explode('\\' ,get_class($this))));
	}
	public function GetFields() {
		return array_merge(array_keys($this->valueObject->GetValues()), array_keys($this->metaValueObject != null ? $this->metaValueObject->GetValues() : array()));
	}

	public function GetTranslatableTable() {
		return $this->GetTable().'_translation';
	}
	public function GetTranslatableFields() {
		return array_keys($this->valueObject->getTranslatableValues());
	}

	protected function UseMetaValueObject($use = true) {
		if ($use) {
			$metaValueObjectClass = '\\'.get_class($this).'\\MetaValueObject';
			$this->metaValueObject = class_exists($metaValueObjectClass) ? new $metaValueObjectClass() : new MetaValueObject();
		} else {
			$this->metaValueObject = null;
		}

		$this->dataAccessObject->SetFields($this->getFields());
		$this->dataAccessObject->SetFields($this->GetTranslatableFields());
	}
		
	public static function Load($data, $language = null) {
		$class = get_called_class();
		$model = new $class();

		$data = $model->dataAccessObject->Load($data, $language);
		
		$model->valueObject->SetValues($data);
		$model->metaValueObject == null ? : $model->metaValueObject->SetValues($data);

		return $model;
	}

	public static function Create($data) {
		$class = get_called_class();
		$model = new $class();

		$model->valueObject->SetValues($data);
		$model->metaValueObject == null ? : $model->metaValueObject->SetValues($data);

		return $model;
	}

	public static function Resource() {
		$model = self::Create(array());
		
		$resourceClass = '\\'.get_called_class().'\\Resource';
		$resource = class_exists($resourceClass) ? new $resourceClass($model) : new Resource($model);
		
		return $resource;
	}

	public function Set($key, $value) {
		$method = 'Set'.str_replace(' ', '', ucfirst(str_replace('_', ' ', $key)));

		if (method_exists($this, $method)) {
			$this->$method($value);
		} else	if (in_array($key, array_keys($this->valueObject->GetValues()))) {
			$this->valueObject->$key = $value;
		} elseif ($this->metaValueObject != null && in_array($key, array_keys($this->metaValueObject->GetValues()))) {
			$this->metaValueObject->$key = $value;
		} else {
			throw new \Flyf\Exceptions\NonExistantPropertyException('The property "'.$key.'" does not exists in class '.get_class($this));
		}
	}

	public function Get($key, $language = LanguageSettings::GetCurrentLanguage()) {
		$this->LoadLanguage($language);
	
		$method = 'Get'.str_replace(' ', '', ucfirst(str_replace('_', ' ', $key)));
		
		if (method_exists($this, $method)) {
			return $this->$method();
		} else	if (in_array($key, array_keys($this->valueObject->GetValues()))) {
			return $this->valueObject->$key;
		} elseif ($this->metaValueObject != null && in_array($key, array_keys($this->metaValueObject->GetValues()))) {
			return $this->metaValueObject->$key;
		} else {
			throw new \Flyf\Exceptions\NonExistantPropertyException('The property "'.$key.'" does not exists in class '.get_class($this));
		}
	}

	private function LoadLanguage($language) {
		if (!in_array(array_keys($this->translations))) {
			
		}
	}
	
	public function Save() {
		$data = $this->metaValueObject != null ? array_merge($this->valueObject->GetValues(), $this->metaValueObject->GetValues()) : $this->valueObject->GetValues();

		$data = $this->dataAccessObject->Save($data);

		$this->valueObject->SetValues($data);
		$this->metaValueObject == null ? : $this->metaValueObject->SetValues($data);
	}
	
	public function Delete($id = false) {
		if ($this->Exists()) {
			$this->dataAccessObject->Delete($id ? : $this->Get('id'));
		}
	}

	public function Trash($id = false) {
		if ($this->exists()) {
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
	
	public function Exists() {
		return ($this->get('id')) ? true : false;
	}
	public function Valid() {
		return $this->valueObject->Validate();
		// TODO
	}

	public function AsArray() {
		$values = array('model' => strtolower(get_class($this)));
		$values = array_merge($values, $this->valueObject->getValues());
		$values = array_merge($values, $this->metaValueObject != null ? $this->metaValueObject->getValues() : array());
		
		return $values;
	}

	public function AsForm() {
		// TODO
	}

	protected function GetDataAccessObject() {
		return $this->dataAccessObject;
	}

	protected function GetValueObject() {
		return $this->valueObject;
	}

	protected function GetMetaValueObject() {
		return $this->metaValueObject;
	}
}
?>
