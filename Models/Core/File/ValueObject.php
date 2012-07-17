<?php
namespace Flyf\Models\Core\File;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject{
	public function __construct(){
		$this->addFields(array(
			"title" => array(
				"type" => "string",
				"maxLength" => 255,
				"required" => true
			),
			"description" => array(
				"type" => "string"
			),
			"url" => array(
				"required" => true
			),
			"filename" => array(
				"required" => true
			),
			"public" => array(
				"type" => "boolean",
				"required" => true,
				"default" => 0
			)
		));
		parent::__construct();
	}
}