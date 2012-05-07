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
	/**
	 * (non-PHPdoc)
	 * @see Flyf\Models\Abstracts.RawModel::Exists()
	 */
	public function Exists(){
		return $this->Get('id') > 0;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Flyf\Models\Abstracts.RawModel::Set()
	 */
	public function Set($key, $value, $language = null){
		if($key=="id") throw new ModelException("You cannot set the id of a model. The id is assigned by the database.");
		parent::Set($key,$value,$language);
	}
	
	/**
	 * Create a new object with this data.
	 * 
	 * @param array $data
	 * @return \Flyf\Models\Abstracts\SimpleModel
	 */
	public static function Create(array $data = array()){
		if(isset($data["id"])) unset($data["id"]);
		return parent::Create($data);
	}
	
	/**
	 * Load the first object from the database that has this data.
	 * 
	 *  Of called with an id, the object with this id is loaded.
	 * 
	 * @param $data The data to match as an array, OR  the id of the object to load.
	 * @return \Flyf\Models\Abstracts\SimpleModel
	 */
	public static function Load($data){
		if(!is_array($data)) $data = array("id"=>$data);
		return parent::Load($data);
	}
}

?>