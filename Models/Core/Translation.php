<?php
namespace Flyf\Models\Core;

use Flyf\Models\Abstracts\DynamicSimpleModel;

class Translation extends DynamicSimpleModel {
	protected $model;
	
	public function __construct(\Flyf\Models\Abstracts\RawModel $model){
		$this->model = $model;
	}
	
	public function loadValueObject(){
		return new \Flyf\Models\Core\Translation\ValueObject($this->model);
	}
}

?>