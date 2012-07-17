<?php
namespace Flyf\Models\Core\Language;

class ValueObject extends \Flyf\Models\Abstracts\RawModel\ValueObject{
	public function __construct(){
		$this->addFields(array(
			"iso" => array(
				"type" => "string",
				"maxLength" => 2,
				"primaryKey" => true,
				"required" => true
			),
			"url" => array(
				"type" => "string",
				"maxLength" => 255
			),
			"name" => array(
				"type" => "string",
				"maxLength" => 55,
				"required" => true,
				"translate" => true
			),
			"active" => array(
				"type" => "boolean",
				"required" => true
			)
		));
		parent::__construct();
	}
}