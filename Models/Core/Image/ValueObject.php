<?php
namespace Flyf\Models\Core\Image;

class ValueObject extends \Flyf\Models\Abstracts\RawModel\ValueObject{
	public function __construct(){
		$this->addFields(array(
			"file_id" => array(
				"type" => "integer",
				"required" => true,
				"reference" => \Flyf\Models\Core\File::Create()
			),
			"width" => array(
				"type" => "integer",
				"required" => true
			),
			"height" => array(
				"type" => "integer",
				"required" => true
			)
		));
		parent::__construct();
	}
}