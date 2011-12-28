<?php
namespace Flyf\Models\Abstracts;

class Resource {
	protected $QueryBuilder;
	private $model;
	
	public function __construct($model) {
		$this->model = get_class($model);
		
		$this->QueryBuilder = new \Flyf\Database\QueryBuilder();

		$this->QueryBuilder->SetTable($model->GetTable());
		$this->QueryBuilder->SetFields($model->getFields());
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
		$class = $this->model;
		
		if (count($dataset = $this->QueryBuilder->Execute())) {
			foreach ($dataset as $data) {
				$objects[] = $class::Create($data);
			}
		}

		return $objects;
	}
}
?>
