<?php
namespace Flyf\Models\Core\Language;

class ValueObject extends \Flyf\Models\Abstracts\ValueObject{
	public $iso;
	public $url;
	public $name;
	public $active;
	
	public function __construct(){
		$this->addAnnotations(array(
			"iso2" => array(
				"type" => "string",
				"maxlength" => 2,
				"primaryIndex" => true,
				"required" => true
			),
			"url" => array(
				"type" => "string",
				"maxlength" => 255
			),
			"name" => array(
				"type" => "string",
				"maxlength" => 55,
				"required" => true
			),
			"active" => array(
				"type" => "boolean",
				"required" => true
			)
		));
	}
}