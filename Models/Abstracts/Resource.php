<?php
class Flyf_Abstract_Model_Resource {
	protected $queryBuilder;
	
	public function __construct() {
		$this->queryBuilder = Flyf::registry('flyf_database_querybuilder');

		$this->queryBuilder->setTable(strtolower(str_replace('_Resource', '', get_class($this))));
		$this->queryBuilder->setFields(array_keys(get_class_vars(str_replace('_Resource', '', get_class($this)).'_ValueObject')));
	}

	public function setLimit($limit) {
		$this->queryBuilder->setLimit($limit);
	}
	public function setOffset($offset) {
		$this->queryBuilder->setOffset($offset);
	}
	public function setOrder($order, $dir) {
		$this->queryBuilder->addOrder($order, $dir);
	}

	public function getCount() {
		return $this->queryBuilder->getCount();
	}
	public function getCountTotal() {
		return $this->queryBuilder->getCountTotal();
	}

	public function build() {
		$objects = array();
		$class = str_replace('_Resource', '', get_called_class());
		
		if (count($dataset = $this->queryBuilder->execute())) {
			foreach ($dataset as $data) {
				$objects[] = $class::create($data);
			}
		}

		return $objects;
	}
}
?>
