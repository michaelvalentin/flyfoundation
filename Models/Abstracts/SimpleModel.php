<?php
namespace Flyf\Models\Abstracts;

use Flyf\Exceptions\ModelException;
use Flyf\Models\Abstracts\RawModel;

/**
 * Abstract models are inteded to be extended and turned
 * into data-models that handles business-data that is meant
 * to be saved to the database.
 *
 * The simple model adds an integer id (Surrogate primary key) to the model, 
 * and enforces that this id is assigned and handled solely by the database.
 * 
 * The simple model is a good starting point for classes that do not
 * need the meta-data of the typical model and neither the ability to
 * trash (soft delete) the model.
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
abstract class SimpleModel extends RawModel {
	public function Exists(){
		return $this->Get('id') > 0;
	}
	
	public function Set($key, $value, $language = null){
		if($key=="id") throw new ModelException("You cannot set the id of a model. The id is assigned by the database.");
	}
	
	public static function Create(array $data){
		if(isset($data["id"])) unset($data["id"]);
		parent::Create($data);
	}
	
	public static function Load($data){
		if(!is_array($data)) $data = array("id"=>$data);
		return parent::Load($data);
	}
}

?>