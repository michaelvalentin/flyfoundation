<?php
namespace Flyf\Models\Blog\Category;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject{
	public function __construct(){
		$this->addFields(array(
				"title" => array(
						"type" => "string",
						"maxLength" => 255,
						"translate" => true
				),
				"description" => array(
						"type" => "string",
						"maxLength" => 255,
						"translate" => true
				),
				"image_id" => array(
						"type" => "integer",
						"reference" => \Flyf\Core\Factory::Image()
				)
			)
		);
		parent::__construct();
	}
}

?>