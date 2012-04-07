<?php
namespace Flyf\Models\Url\Rewrite;

class ValueObject extends \Flyf\Models\Abstracts\SimpleModel\ValueObject{
	public $seo;
	public $system;
	
	public function __construct(){
		parent::__construct();
		$this->addAnnotations(array(
				"seo" => array(
						"type" => "string",
						"maxLength" => 255,
						"required" => true
				),
				"system" => array(
						"type" => "string",
						"maxLength" => 255,
						"required" => true
				)
		));
	}
}
?>
