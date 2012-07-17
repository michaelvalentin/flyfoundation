<?php
namespace Flyf\Models\Blog\Post;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject{
	public function __construct(){
		$this->addFields(array(
				"writer_id" => array(
						"type" => "integer",
						"reference" => \Flyf\Models\Blog\Writer::Create()
				),
				"category_id" => array(
						"type" => "integer",
						"reference" => \Flyf\Models\Blog\Category::Create()
				),
				"title" => array(
						"type" => "string",
						"maxLength" => 255,
						"translate" => true
				),
				"intro" => array(
						"type" => "string",
						"translate" => true
				),
				"content" => array(
						"type" => "string",
						"translate" => true,
						"required" => true
				),
				"intro_image_id" => array(
						"type" => "integer",
						"reference" => \Flyf\Core\Factory::Image()
				),
				"image_id" => array(
						"type" => "integer",
						"reference" => \Flyf\Core\Factory::Image()
				),
				"publish_date" => array(
						"type" => "datetime"
				)
			)
		);
		parent::__construct();
	}
}