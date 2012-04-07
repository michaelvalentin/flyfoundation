<?php
namespace Flyf\Models\Abstracts\Model;

class ValueObject extends \Flyf\Models\Abstracts\SimpleModel\ValueObject {
	public $created;
	public $modified;
	public $trashed;
	
	public function __construct(){
		parent::__construct();
		$this->addAnnotations(array(
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
	}
}
?>
