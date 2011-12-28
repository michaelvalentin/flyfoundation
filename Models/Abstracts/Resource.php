<?php
namespace Flyf\Models\Abstracts;

class Resource {
	protected $QueryBuilder;
	
	public function __construct() {
		$this->QueryBuilder = new \Flyf\Database\QueryBuilder();

		$this->QueryBuilder->SetTable(strtolower(str_replace('_Resource', '', get_class($this))));
		$this->QueryBuilder->SetFields(array_keys(get_class_vars(str_replace('_Resource', '', get_class($this)).'_ValueObject')));
	}

	public function SetLimit($limit) {
		$this->QueryBuilder->setLimit($limit);
	}
	public function SetOffset($offset) {
		$this->QueryBuilder->setOffset($offset);
	}
	public function SetOrder($order, $dir) {
		$this->QueryBuilder->addOrder($order, $dir);
	}

	public function GetCount() {
		return $this->QueryBuilder->GetCount();
	}
	public function GetCountTotal() {
		return $this->QueryBuilder->GetCountTotal();
	}

	public function Build() {
		$objects = array();
		$class = str_replace('_Resource', '', get_called_class());
		
		if (count($dataset = $this->QueryBuilder->ExecuteQuery())) {
			foreach ($dataset as $data) {
				$objects[] = $class::Create($data);
			}
		}

		return $objects;
	}
}
?>
