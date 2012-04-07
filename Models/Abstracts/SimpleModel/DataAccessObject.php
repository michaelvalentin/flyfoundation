<?php
namespace Flyf\Models\Abstracts\SimpleModel;
use \Flyf\Database\DatabaseType;

/*
 * @author Michael Valentin <mv@signifly.com>
 * @version 2011-01-06
 * @dependencies QueryBuilder
 */
class DataAccessObject extends \Flyf\Models\Abstracts\RawModel\DataAccessObject{
	// The query builder used to build queries (o'rly)
	protected $queryBuilder;

	// The base table
	protected $table;
	// The base fields
	protected $fields = array();

	// The translation table
	protected $TranslationTable;
	// The translation fields
	protected $TranslationFields = array();
	
	/**
	 * The constructor is only used to instantiate
	 * an instance of the QueryBuilder.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @inheritdoc
	 */
	public function Load($data){
		if(is_array($data)){
			return parent::Load($data);
		}else{
			return parent::Load(array('id'=>$data));
		}
	}
	
	/**
	 * Both updates and inserts into the external data source.
	 * The parameter must be an associative array, where the
	 * keys represents the fields of the table in the database
	 * and the values represents ... The values.
	 *
	 * If an 'id' key is in the associative array, the row associated
	 * with the id in the database is updated. If an 'id' key is not
	 * to be found, the method will insert a new row in the database.
	 *
	 * The method automatically adds modified and created date-stamps,
	 * if 'date_modified' and 'date_created' exists in the data given.
	 * 
	 * If the "operation" is an insert-"operation" it also inserts the
	 * newly created id into the data, before returning it.
	 *
	 * @param array $data (an associative array of the data to be saved)
	 * @return array $data (the data after saving)
	 */
	public function Save($data) {
		if (isset($data['id']) && $data['id']) {
			return parent::Save($data,false);
		}else{
			return parent::Save($data,true);
		}	
	}

	/**
	 * The method simply interates through the given translations
	 * and saves those to the specified translation-table. 
	 * 
	 * If a translation does not exists, it will be created in
	 * the database. 
	 *
	 * @param integer $model_id (the id of the model)
	 * @param array $translations (the translations to be saved)
	 * @return array (the saved translations)
	 */
	public function SaveTranslations($model_id, $translations) {
		foreach ($translations as $key => $data) {
			$data['model_id'] = $model_id;
			$data['language'] = $key;
			
			$isUpdate = true;
			foreach ($this->primaryKey as $key) {
				if (!$isUpdate) {
					throw new \Exception('Trying to save translation without all primary keys included in data');
				}
			
				if (!in_array($key, array_keys($data))) {
					$isUpdate = false;
				}
			}
		
			if ($isUpdate) {
				$this->queryBuilder->SetType('update');
			
				foreach ($this->primaryKey as $key) {
					$this->queryBuilder->AddCondition($key.' = :'.$key);
					$this->queryBuilder->BindParam($key, $data[$key]);
				}
			} else {
				$this->queryBuilder->SetType('insert');
			}
		
			$this->queryBuilder->SetTable($this->TranslationTable);

			$this->queryBuilder->SetFields(array_keys($data));
			$this->queryBuilder->SetValues(array_values($data));
		
			$this->queryBuilder->SetLimit(1);

			if (($translation_id = $this->queryBuilder->Execute()) !== null) {
				$data['id'] = isset($data['id']) && $data['id'] ? $data['id'] : $translation_id;
			}

			$translations[$key] = $data;
		}

		return $translations;
	}

	/**
	 * Assembles a delete-statement to be executed. The
	 * id parameter determines which row to delete in the
	 * database. 
	 *
	 * @param integer $id (the id of the model to delete)
	 */
	public function Delete($id) {
		$this->DeleteTranslations($id);
		
		$this->queryBuilder->SetType('delete');
		$this->queryBuilder->SetTable($this->table);
		
		$this->queryBuilder->AddCondition('`id` = :id');
		$this->queryBuilder->BindParam('id', $id);
		$this->queryBuilder->SetLimit(1);

		$this->queryBuilder->Execute();
	}

	/**
	 * Deletes all translations of a model. Like the Delete
	 * method it assembles an delete-statement and executes
	 * it towards the database using the query builder.
	 *
	 * @param integer $model_id (the model_id of the translations to delete)
	 */
	public function DeleteTranslations($model_id) {
		$this->queryBuilder->SetType('delete');
		$this->queryBuilder->SetTable($this->TranslationTable);
		
		$this->queryBuilder->AddCondition('`model_id` = :model_id');
		$this->queryBuilder->BindParam('model_id', $model_id);

		$this->queryBuilder->Execute();
	}
	
	public function TableExists(){
		$db = \Flyf\Database\Connection::GetConnection();
		$db->Prepare("SHOW TABLES LIKE :name");
		$db->Bind(array("name"=>$this->table));
		$res = $db->ExecuteQuery();
		if(count($res["result"]) > 0) return true;
		return false;
	}
	
	public function BuildTable(){
		if(!DEBUG) return;
		if($this->TableExists()){
			//TODO implement table update features...
		}else{
			$table = new \Flyf\Database\TableBuilder($this->table);
			foreach($this->fieldDefinitions as $l=>$v){
				$table->AddField($l, $v);
			}
			foreach($this->ClassDefinitions as $l=>$v){
				if($v["index"]){
					$table->AddIndex($l,$v);
				}elseif($v["constraint"]){
					$table->AddConstraint($v);
				}
			}
			$table->CreateTable();
		}
	}
}
