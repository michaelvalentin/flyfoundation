<?php
namespace Flyf\Models;

class Test extends \Flyf\Models\Abstracts\Model {
	protected function __construct() {
		parent::__construct();

		$this->UseMetaValueObject(false);
	}
}
?>
