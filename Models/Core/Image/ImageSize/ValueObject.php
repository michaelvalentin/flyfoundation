<?php
namespace Flyf\Models\Core\Image\ImageSize;

class ValueObject extends \Flyf\Models\Abstracts\RawModel\ValueObject{
	public function __construct(){
		$this->addFields(array(
			"image_id" => array(
				"type" => "integer",
				"required" => true,
				"reference" => \Flyf\Models\Core\Image::Create()
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