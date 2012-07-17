<?php
namespace Flyf\Models\Abstracts;

use Flyf\Models\Abstracts\SimpleModel;

/**
 * The standard model supports a mandatory primary-index id, created-time, modified time
 * and the ability to trash (delete with the ability to restore...) models. The model is
 * simple and lightweight but still relatively powerfull.  
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
abstract class Model extends SimpleModel {
	public function Save(){
		if(!$this->Exists()){
			$this->Set("created",new \DateTime());
		}else{
			$this->Set("modified",new \DateTime());
		}
		return parent::Save();
	}
	
	//!TODO We must implement features to ensure that trashed elements aren't returned in.. Say the resource-object... And cannot be loaded..
	public function Trash(){
		$this->Set("trashed",new \DateTime());
		return parent::Save();
	}
	
	public function Untrash(){
		$this->Set("trashed",null);
		return parent::Save();
	}
}

?>