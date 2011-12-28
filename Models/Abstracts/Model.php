<?php
abstract class Flyf_Abstract_Model {
	private $dataAccessObject = null;
	private $valueObject = null;
	private $metaValueObject = null;

	protected $translations = array();


	protected function __construct(DataAccessObject $dao = null, ValueObject $vo = null, MetaValueObject $mvo=null) {
		$this->dataAccessObject = $dao ? : new DataAccessObject();
		$this->valueObject = $vo ? : new ValueObject();
		$this->metaValueObject = $mvo ? : new MetaValueObject();
		//$this->dataAccessObject->Init(); //Example..
	}
	
	/**
	 * @return the $dataAccessObject
	 */
	protected function getDataAccessObject() {
		return $this->dataAccessObject;
	}

	/**
	 * @return the $valueObject
	 */
	protected function getValueObject() {
		return $this->valueObject;
	}

	/**
	 * @return the $metaValueObject
	 */
	protected function getMetaValueObject() {
		return $this->metaValueObject;
	}
	
	public static function load($data) {
		$class = get_called_class();
		$model = new $class();

		$data = $model->dataAccessObject->load($data);
		
		$model->valueObject->setValues($data);
		$model->metaValueObject == null ? : $model->metaValueObject->setValues($data);

		return $model;
	}

	public static function create($data) {
		$class = get_called_class();
		$model = new $class();

		$model->valueObject->setValues($data);
		$model->metaValueObject == null ? : $model->metaValueObject->setValues($data);

		return $model;
	}

	public static function resource() {
		$class = get_called_class().'_Resource';

		if (class_exists($class)) {
			return new $class();
		}
	}

	public function set($key, $value) {
		//TODO: Try to find a setterMethod
		$this->valueObject->$key = $value;
		$this->metaValueObject->$key = $value;
	}

	public function get($key) {
		$method = 'get'.str_replace(' ', '', ucfirst(str_replace('_', ' ', $key)));
		
		if (method_exists($this, $method)) {
			return $this->$method();
		} else	if (in_array($key, array_keys($this->valueObject->getValues()))) {
			return $this->valueObject->$key;
		} elseif ($this->metaValueObject != null && in_array($key, array_keys($this->metaValueObject->getValues()))) {
			return $this->metaValueObject->$key;
		}
		throw new \Exception("The requested value '$key' could not be found in".get_class($this)); //TOOD: Speicifc exception type?
	}
	
	public function save() {
		$data = $this->metaValueObject != null ? array_merge($this->valueObject->getValues(), $this->metaValueObject->getValues()) : $this->valueObject->getValues();

		$data = $this->dataAccessObject->save($data);

		$this->valueObject->setValues($data);
		$this->metaValueObject == null ? : $this->metaValueObject->setValues($data);
	}
	
	public function delete($id = false) {
		if ($this->exists()) {
			$this->dataAccessObject->delete($id ? : $this->get('id'));
		}
	}

	public function trash($id = false) {
		if ($this->exists() && $this->metaValueObject != null) {
			$data = $this->dataAccessObject->trash($id ? : $this->get('id'));

			$this->valueObject->setValues($data);
			$this->metaValueObject->setValues($data);
		}
		//Todo: Throw exception??
	}

	public function untrash($id = false) {
		if ($this->exists() && $this->metaValueObject != null) {
			$data = $this->dataAccessObject->untrash($id ? : $this->get('id'));

			$this->valueObject->setValues($data);
			$this->metaValueObject->setValues($data);
		}
	}
	
	public function exists() {
		return ($this->get('id')) ? true : false;
	}
	public function valid() {
		// TODO
	}

	public function toArray() {
		$values = array('model' => strtolower(get_class($this)));
		$values = array_merge($values, get_object_vars($this->valueObject));
		
		return $values;
	}

	public function toForm() {
		// TODO
	}
}
?>
