<?php
namespace Flyf\Models\Core;

use Flyf\Models\Abstracts\DynamicSimpleModel;

class Translation extends DynamicSimpleModel {
	protected $model;
	protected $vo;
	
	public function __construct(\Flyf\Models\Abstracts\RawModel $model){
		$this->model = $model;
		$this->vo = $model->GetEmptyValueObject();
		parent::__construct();
	}
	
	public function LoadModel(array $data){
		$result = $this->dataAccessObject->Load($data);
		if($result){
			$class = get_called_class();
			$model = new $class($this->model);
			$model->valueObject->SetTextValues($result);
			return $model;
		}else{
			return false;
		}
	}
	
	public function CreateModel(array $data = array()){
		$class = get_called_class();
		$model = new $class($this->model);
		$model->valueObject->SetValues($data);
		return $model;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Flyf\Models\Abstracts.RawModel::GetTable()
	 */
	public function GetTable(){
		return $this->model->GetTable()."_language";
	}
	
	public function loadValueObject(){
		return new \Flyf\Models\Core\Translation\ValueObject($this->vo);
	}
}

?>