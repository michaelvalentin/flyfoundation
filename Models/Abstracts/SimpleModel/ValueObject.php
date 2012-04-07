<?php
namespace Flyf\Models\Abstracts\SimpleModel;

class ValueObject extends \Flyf\Models\Abstracts\RawModel\ValueObject {
	public $id;
	
	public function __construct() {
		parent::__construct();
		$this->addAnnotations(array(
				"id" => array(
					"type" => "integer",
					"maxLength" => false,
					"primaryKey" => true,
					"required" => true,
					"autoIncrement" => true,
					"unsigned" => true
				)
			)
		);
	}
}

?>