<?php
namespace Flyf\Models\Cms\Page;

class ValueObject extends \Flyf\Models\Abstracts\ValueObject{
	public $title;
	public $content;
	
	public function __construct(){
		$this->addAnnotations(array(
			"title" => array(
				"type" => "string",
				"required" => true
			),
			"content" => array(
				"type" => "string",
				"required" => true
			)
		));
	}
}