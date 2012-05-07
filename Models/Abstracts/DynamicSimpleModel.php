<?php
namespace Flyf\Models\Abstracts;

use Flyf\Exceptions\DynamicModelException;

use Flyf\Models\Abstracts\SimpleModel;

abstract class DynamicSimpleModel extends SimpleModel {
	
	/**
	 * Constructor is now public to allow instansiation
	 * 
	 * Instansiation can't happen through the static methods (like in
	 * the normal models) as this is a dynamic model..
	 */
	public function __construct(){
		parent::__construct();
	}
	
	public abstract function LoadModel(array $data);
	
	public abstract function CreateModel(array $data = array());
	
	public static function Load($data) {
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
	
	public static function CreateFromText(array $data = array()) {
		throw new DynamicModelException("Model is dynamic, and static methods doesn't make sense to call.");
	}
}

?>