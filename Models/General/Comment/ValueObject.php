<?php
namespace Flyf\Models\General\Comment;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject{
	public function __construct(){
		$this->addFields(array(
			"user_id" => array(
				"type" => "integer",
				"reference" => \Flyf\Models\Core\User::Create()
			),
			"title" => array(
				"type" => "string",
				"maxLength" => 255,
				"required" => true
			),
			"content" => array(
				"type" => "string"
			),
			"email" => array(
				"required" => true
			),
			"name" => array(
				"required" => true
			),
			"ip" => array(
				"maxLenght" => 50,
				"required" => true
			),
			"aproved" => array(
				"type" => "boolean",
				"default" => 1
			)
		));
		parent::__construct();
	}
}