<?php
namespace Flyf\Models\Abstracts;

use Flyf\Exceptions\DynamicModelException;

use Flyf\Models\Abstracts\SimpleModel;

class DynamicSimpleModel extends SimpleModel {
	
	public static function Load(array $data) {
		throw new DynamicModelException("Model is dynamic, and static methods doesn't make sense to call.");
	}

	public static function Create(array $data = array()) {
		throw new DynamicModelException("Model is dynamic, and static methods doesn't make sense to call.");
	}

	public static function Delete(array $primaryKey) {
		throw new DynamicModelException("Model is dynamic, and static methods doesn't make sense to call.");
	}
	
	public static function Resource() {
		throw new DynamicModelException("Model is dynamic, and static methods doesn't make sense to call.");
	}
}

?>