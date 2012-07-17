<?php
namespace Flyf\Models\Core\User;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject{
	public function __construct(){
		$this->addFields(array(
				"username" => array(
						"type" => "string",
						"maxLength" => 255,
						"required" => true
				),
				"password_hash" => array(
						"type" => "string",
						"maxLength" => 255,
						"required" => true
				),
				"user_salt" => array(
						"type" => "string",
						"maxLength" => 30,
						"required" => true
				),
				"first_name" => array(
						"type" => "string",
						"maxLenght" => 255
				),
				"last_name" => array(
						"type" => "string",
						"maxLength" => 255
				),
				"email" => array(
						"type" => "string",
						"maxLength" => 255,
						"required" => true
				),
				"last_login" => array(
						"type" => "datetime"
				),
				"last_login_ip" => array(
						"type" => "string",
						"maxLength" => 50
				)
		));
		parent::__construct();
	}
}

?>