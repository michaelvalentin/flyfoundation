<?php
namespace Flyf\Models\Url;

class Redirect extends \Flyf\Models\Abstracts\Model {
	protected function __construct() {
		parent::__construct();
		$this->UseMetaValueObject(false);
	}
}
?>
