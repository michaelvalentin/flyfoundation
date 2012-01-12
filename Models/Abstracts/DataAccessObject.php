<?php
namespace Flyf\Models\Abstracts;
use \Flyf\Database\DatabaseType;

/**
 * The data access object facilitates communication
 * with the external data source (database) for the 
 * model. When a model wants to save/delete/load content
 * it propagates its calls/uses the data access object
 * to execute the nessecary queries and fetch the desired
 * data.
 *
 * Said in other words, the data access object is the
 * bridge between the models used in the application
 * and the persistent storage.
 *
 * The data access object uses an instance of the 
 * QueryBuilder to build an execute it's queries.
 *
 * The data access object is very close to a classic
 * CRUD mechanism, but is designed with inheritance
 * in mind. If unique behaviour or added behvaiour is
 * needed by a model, one can simply create a new
 * data access object (given the right naming-convention)
 * and inherit from the DataAccessObject (this class).
 *
 * The model will load the custom data access object
 * without any further editing.
 *
 * @author Henrik Haugbølle <hh@signifly.com>
 * @version 2011-01-06
 * @dependencies QueryBuilder
 */
class DataAccessObject {
	// The query builder used to build queries (o'rly)
	protected $QueryBuilder;

	// The base table
	protected $Table;
	// The base fields
	protected $Fields = array();

	// The translation table
	protected $TableTranslation;
	// The translation fields
	protected $FieldsTranslation = array();

	// The field definitions
	protected $FieldDefinitions = array();
	// The primary keys
	protected $PrimaryKeys = array();

	/**
	 * The constructor is only used to instantiate
	 * an instance of the QueryBuilder.
	 */
	public function __construct() {
		$this->QueryBuilder = new \Flyf\Database\QueryBuilder();
	}

	/**
	 * Set method for setting the base table.
	 *
	 * @param string $table
	 */
	public function SetTable($table) {
		$this->Table = $table;
	}

	/**
	 * Set method for setting the base fields.
	 *
	 * @param array $fields
	 */
	public function SetFields($fields) {
		$this->Fields = $fields;
	}

	/**
	 * Set method for setting the translation table.
	 *
	 * @param string $tableTranslation
	 */
	public function SetTableTranslation($tableTranslation) {
		$this->TableTranslation = $tableTranslation;
	}

	/**
	 * Set method for setting the translation fields.
	 *
	 * @param array $fieldsTranslation
	 */
	public function SetFieldsTranslation($fieldsTranslation) {
		$this->FieldsTranslation = $fieldsTranslation;
	}

	/**
	 * Set method for setting the field defintions.
	 *
	 * @param array $fieldsTranslation
	 */
	public function SetFieldDefinitions($fieldDefinitions) {
		$this->PrimaryKeys = array();
		
		foreach ($fieldDefinitions as $key => $definition) {
			if (isset($definition['primaryIndex']) && $definition['primaryIndex'] == true) {
				$this->PrimaryKeys[] = $key;
			}
		}
		
		$this->FieldDefinitions = $fieldDefinitions;
	}

	/**
	 * The Load method can either be called with an id or with
	 * a various range as parameters (given as an associative array).
	 *
	 * The method builds a select statement from the parameters
	 * and fetches the results from the database using the query builder.
	 *
	 * @param mixed $data (either an id as integer, or an associative array)
	 * @return array (the data as an associative array)
	 */
	public function Load($data) {
		if (is_array($data)) {
			$this->QueryBuilder->SetType('select');
			$this->QueryBuilder->SetTable($this->Table);
			$this->QueryBuilder->SetFields($this->Fields);
			
			$this->QueryBuilder->SetLimit(1);

			foreach ($data as $key => $value) {
				$this->QueryBuilder->AddCondition('`'.$key.'` = :'.$key);
				$this->QueryBuilder->BindParam($key, $value);
			}

			if (count($result = $this->QueryBuilder->Execute())) {
				return $result[0];
			} else {
				return array();	
			}
		} else {
			return $this->Load(array('id' => $data));
		}
	}

	/**
	 * The LoadTranslation method is, as its name says, used 
	 * to load a translation of a model.
	 *
	 * It uses the translation-table and translation-fields.
	 * 
	 * @param integer $model_id (the id of the model)
	 * @param string $language (the language to load)
	 */
	public function LoadTranslation($model_id, $language) {
		$this->QueryBuilder->SetType('select');
		$this->QueryBuilder->SetTable($this->TableTranslation);
		$this->QueryBuilder->SetFields(array_merge(array('id'), $this->FieldsTranslation));
		
		$this->QueryBuilder->SetLimit(1);

		$this->QueryBuilder->AddCondition('`model_id` = :model_id');
		$this->QueryBuilder->BindParam('model_id', $model_id);
		$this->QueryBuilder->AddCondition('`language` = :language');
		$this->QueryBuilder->BindParam('language', $language);

		if (count($result = $this->QueryBuilder->Execute())) {
			return $result[0];
		} else {
			return array();	
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
		print_r($this->FieldDefinitions);
		print_r($this->PrimaryKeys);

		$isUpdate = true;
		foreach ($this->PrimaryKeys as $key) {
			if (!$isUpdate) {
				throw new \Exception('Trying to save without all primary keys included in data');
			}
			
			if (!in_array($key, array_keys($data))) {
				$isUpdate = false;
			}
		}
		
		if ($isUpdate) {
			$this->QueryBuilder->SetType('update');
			
			foreach ($this->PrimaryKeys as $key) {
				$this->QueryBuilder->AddCondition($key.' = :'.$key);
				$this->QueryBuilder->BindParam($key, $data[$key]);
			}
		
			if (array_key_exists('date_modified', $data)) {
				$data['date_modified'] = date('Y-m-d H:i:s');
			}
		} else {
			$this->QueryBuilder->SetType('insert');

			if (array_key_exists('date_created', $data)) {
				$data['date_created'] = date('Y-m-d H:i:s');
			}
		}
		
		$this->QueryBuilder->SetTable($this->Table);

		$this->QueryBuilder->SetFields(array_keys($data));
		$this->QueryBuilder->SetValues(array_values($data));
		
		$this->QueryBuilder->SetLimit(1);

		if (($id = $this->QueryBuilder->Execute()) !== null) {
			$data['id'] = isset($data['id']) && $data['id'] ? $data['id'] : $id;
		}

		return $data;
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
			foreach ($this->PrimaryKeys as $key) {
				if (!$isUpdate) {
					throw new \Exception('Trying to save translation without all primary keys included in data');
				}
			
				if (!in_array($key, array_keys($data))) {
					$isUpdate = false;
				}
			}
		
			if ($isUpdate) {
				$this->QueryBuilder->SetType('update');
			
				foreach ($this->PrimaryKeys as $key) {
					$this->QueryBuilder->AddCondition($key.' = :'.$key);
					$this->QueryBuilder->BindParam($key, $data[$key]);
				}
			} else {
				$this->QueryBuilder->SetType('insert');
			}
		
			$this->QueryBuilder->SetTable($this->TableTranslation);

			$this->QueryBuilder->SetFields(array_keys($data));
			$this->QueryBuilder->SetValues(array_values($data));
		
			$this->QueryBuilder->SetLimit(1);

			if (($translation_id = $this->QueryBuilder->Execute()) !== null) {
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
		$this->QueryBuilder->SetType('delete');
		$this->QueryBuilder->SetTable($this->Table);
		
		$this->QueryBuilder->AddCondition('`id` = :id');
		$this->QueryBuilder->BindParam('id', $id);
		$this->QueryBuilder->SetLimit(1);

		$this->QueryBuilder->Execute();
	}

	/**
	 * Deletes all translations of a model. Like the Delete
	 * method it assembles an delete-statement and executes
	 * it towards the database using the query builder.
	 *
	 * @param integer $model_id (the model_id of the translations to delete)
	 */
	public function DeleteTranslations($model_id) {
		$this->QueryBuilder->SetType('delete');
		$this->QueryBuilder->SetTable($this->TableTranslation);
		
		$this->QueryBuilder->AddCondition('`model_id` = :model_id');
		$this->QueryBuilder->BindParam('model_id', $model_id);

		$this->QueryBuilder->Execute();
	}

	/**
	 * Used when a model has a meta value object attached.
	 * The method takes the id of the model, and simply
	 * updates the 'date_trashed' field in the database to
	 * the current time, thereby declaring the model as
	 * "trashed".
	 * 
	 * @param integer $id (the id of the model to trash)
	 */
	public function Trash($id) {
		$data = array(
			'date_trashed' => date('Y-m-d H:i:s')
		);
		
		$this->QueryBuilder->SetType('update');
		$this->QueryBuilder->SetTable($this->Table);
		$this->QueryBuilder->SetFields(array_keys($data));
		$this->QueryBuilder->SetValues(array_values($data));
		$this->QueryBuilder->AddCondition('id = :id');
		$this->QueryBuilder->BindParam('id', $id);
		$this->QueryBuilder->SetLimit(1);
		
		$this->QueryBuilder->Execute();

		return $data;
	}
	
	/**
	 * Used when a model has a meta value object attached.
	 * The method takes the id of the model, and simply
	 * updates the 'date_trashed' field in the database to
	 * the 0, thereby decalring the model as "untrashed"
	 * or not-trashed.
	 * 
	 * @param integer $id (the id of the model to untrash)
	 */
	public function Untrash($id) {
		$data = array(
			'date_trashed' => 0
		);
		
		$this->QueryBuilder->SetType('update');
		$this->QueryBuilder->SetTable($this->Table);
		$this->QueryBuilder->SetFields(array_keys($data));
		$this->QueryBuilder->SetValues(array_values($data));
		$this->QueryBuilder->AddCondition('id = :id');
		$this->QueryBuilder->BindParam('id', $id);	
		$this->QueryBuilder->SetLimit(1);

		$this->QueryBuilder->Execute();

		return $data;
	}
	
	public function GetTableName(){
		$class = get_class($this);
		$parts = explode("\\", $class);
		return $parts[count($parts)-2];
	}
	
	/**
	 * Query the database to check if the table exists 
	 */
	public function TableExists(){
		$conn = \Flyf\Database\Connection::GetConnection();
		$conn->Prepare("SHOW TABLES LIKE :name");
		$conn->Bind(array(":name"=>$this->Table));
		$res = $conn->ExecuteQuery();
		return count($res["result"]) == 1 ? true : false;
	}
	
	/**
	 * Build a table from FieldDefinitions from ValueObject
	 * @param array $fieldDefinitions The field definitions to build from.
	 */
	public function CreateTable(){
		$fieldDefinitions = $this->FieldDefinitions;
		
		$table = new \Flyf\Database\TableBuilder($this->Table);
		foreach($fieldDefinitions as $k => $v){
			$limit = isset($v["max-length"]) && is_int($v["max-length"]) ? $v["max-length"] : false;
			$type = isset($v["type"]) ? $v["type"] : "string";
			$table->AddField($k, $type, $limit);
			if(isset($v["required"]) && $v["required"]) $table->SetNotNull($k);
			if(isset($v["primaryIndex"]) && $v["primaryIndex"]) $table->SetPrimaryKey($k);
		}
		$table->CreateTable();
	}
	
	/**
	 * Custom post processing of newly build table..
	 */
	protected function PrepareNewTable(){
		//Only for extending..
	}
}
?>
