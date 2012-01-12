<?php
namespace Flyf\Models\Test;

class ValueObject extends \Flyf\Models\Abstracts\ValueObject {
	public $id;

	public $title;
	public $content;

	public function __construct() {
		$this->addAnnotations(array(
			"id" => array(
				"type" => "integer",
				"max-length" => 11,
				"primaryIndex" => true,
				"required" => true
			),
			"title" => array(
				"type" => "string",
				"max-length" => 255,
				'primaryIndex' => true
			),
			"content" => array(
				"type" => "string",
				"max-length" => 1000
			)
		));
	}
}
?>
