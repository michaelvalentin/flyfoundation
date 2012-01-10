<?php
namespace Flyf\Models\Url;

class Rewrite extends \Flyf\Models\Abstracts\Model {
	protected function __construct() {
		parent::__construct();
		$this->UseMetaValueObject(false);
	}
}
?>
