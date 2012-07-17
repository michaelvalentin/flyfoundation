<?php
namespace Flyf\Models\Abstracts\AliasedModel;

class ValueObject extends \Flyf\Models\Abstracts\Model\ValueObject {
	public function __construct(){
		$this->addFields(array(
				"alias" => array(
					"required" => true
				)
		));
		parent::__construct();
	}
}
?>
