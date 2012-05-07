<?php
namespace Flyf\Models\Core\Translation;

class ValueObject extends \Flyf\Models\Abstracts\SimpleModel\ValueObject {
	protected $languageModel;
	
	public function __construct(\Flyf\Models\Abstracts\RawModel\ValueObject $modelValueObject){
		$this->languageModel = $modelValueObject;
		$this->buildDefinitions();
		parent::__construct();
	}
	
	/**
	 * Build the definitions for this object, based on the inputted model
	 */
	protected function buildDefinitions(){
		$this->addFields($this->getIdentifier());
		$this->addModelProperty(array(
										"name" => "model_lookup_index",
										"index"=>"UNIQUE INDEX",
										"columns"=>array_keys($this->getIdentifier())
									)
								);
		$fieldsToTranslate = $this->languageModel->GetTranslatableFieldsDefinitions();
		foreach($fieldsToTranslate as $fieldName=>$annotations){
			$annotations["translate"] = false;
			$annotations["unique"] = false;
			$annotations["require"] = $annotations["requireTranslation"];
			$annotations["default"] = false;
			$annotations["autoIncrement"] = false;
			$annotations["primaryKey"] = false;
			$this->addField($fieldName,$annotations);
		}
	}
	
	/**
	 * What is the fields by which this translation is uniquely identified - except for the surrogate ID?
	 * 
	 * @return array (The definition of the relevant fields
	 */
	protected function getIdentifier() {
		$own = array(
				"language_iso"=>array(
										"type" => "string",
										"maxLength" => "2",
										"required" => true,
										"reference" => \Flyf\Models\Core\Language::Create(),
										"reference_column" => "iso",
										"reference_on_update" => "CASCADE",
										"reference_on_delete" => "CASCADE"
									)
					);
		$modelKey = $this->languageModel->GetPrimaryKeyDefinition();
		foreach($modelKey as $column=>$options){
			unset($modelKey[$column]);
			$options["primaryKey"] = false;
			$options["autoIncrement"] = false;
			$column = "model_".$column;
			$modelKey[$column] = $options;
		}
		return array_merge($own,$modelKey);
	}
}

?>