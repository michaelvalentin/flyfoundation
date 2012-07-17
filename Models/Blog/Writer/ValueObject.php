<?php
namespace Flyf\Models\Blog\Writer;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject{
	public function __construct(){
		$this->addFields(array(
				"user_id" => array(
						"type" => "integer",
						"required" => true,
						"reference" => \Flyf\Models\Core\User::Create()	
				),
				"title" => array(
						"type" => "string",
						"maxLength" => 255,
						"translate" => true,
						"required" => true
				),
				"description" => array(
						"type" => "string",
						"maxLength" => 255,
						"translate" => true
				),
				"order" => array(
						"type" => "integer"
				)
			)
		);
		parent::__construct();
	}
}

?>