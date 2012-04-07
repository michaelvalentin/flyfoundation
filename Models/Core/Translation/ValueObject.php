<?php
namespace Flyf\Models\Core\Translation;

class ValueObject extends \Flyf\Models\Abstracts\SimpleModel\ValueObject {
	public $language;
	protected $languageModel;
	
	public function __construct(\Flyf\Models\Abstracts\RawModel\ValueObject $modelValueObject){
		parent::__construct();
		$this->$excludedFields[] = "languageModel";
		$this->AddAnnotations(array(
				"language" => array(
						"type" => "string",
						"maxLength" => "2",
						"required" => true,
						"reference" => new \Flyf\Models\Core\Language(),
						"reference_column" => "iso",
						"reference_on_update" => "CASCADE",
						"reference_on_delete" => "CASCADE"
						)
				));
		$this->languageModel = $modelValueObject;
	}
	
	/**
	 * Get all defined fields with annotations
	 * @return array An array of all fields (keys) with eventual 
	 * annotations as array (values) 
	 */
	public function GetFieldDefinitions() {
		$own = parent::GetFieldDefinitions();
		$modelKey = $this->languageModel->GetPrimaryKey();
		$translate = $this->languageModel->GetTranslationFieldDefinitions();
		return array_merge($own,$modelKey,$translate);
	}

	public function GetTranslationFields() {
		return array(); //A translation should never be translated....
	}
}

?>