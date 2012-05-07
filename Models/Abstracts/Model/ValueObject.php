<?php
namespace Flyf\Models\Abstracts\Model;

class ValueObject extends \Flyf\Models\Abstracts\SimpleModel\ValueObject {
	public function __construct(){
		$this->addFields(array(
				"created" => array(
						"type" => "DATETIME"
				),
				"modified" => array(
						"type" => "DATETIME"
				),
				"trashed" => array(
						"type" => "DATETIME"
				)
		));
		parent::__construct();
	}
}
?>
