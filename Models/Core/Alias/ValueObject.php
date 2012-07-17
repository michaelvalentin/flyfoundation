<?php
namespace Flyf\Models\Core\Alias;

class ValueObject extends \Flyf\Models\Abstracts\RawModel\ValueObject{
	public function __construct(){
		$this->addFields(array(
			"alias" => array(
				"primaryKey" => true		
			)
		));
		parent::__construct();
	}
}