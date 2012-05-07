<?php
namespace Flyf\Models\Abstracts\SimpleModel;

class ValueObject extends \Flyf\Models\Abstracts\RawModel\ValueObject {
	public function __construct() {
		$this->addField("id", array(
					"type" => "integer",
					"maxLength" => false,
					"primaryKey" => true,
					"required" => true,
					"autoIncrement" => true,
					"unsigned" => true
				)
		);
		parent::__construct();
	}
}

?>