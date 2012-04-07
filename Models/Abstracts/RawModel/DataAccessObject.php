<?php
namespace Flyf\Models\Abstracts\RawModel;
use Flyf\Exceptions\InvalidArgumentException;

use Flyf\Exceptions\DangerousQueryException;

use Flyf\Util\Debug;

use Flyf\Exceptions\ModelException;

use \Flyf\Database\DatabaseType;

/**
 * A DataAccessObject (DAO) to communicate with the database. The DAO
 * supports basic CRUD operations and some other utility functions.
 * 
 * The DAO is supposed to be extended by other objects, 
 * that will propably incorporate other types of requests into it.
 * 
 * The DAO is supposed to handle requests related to working with
 * one particular record, whereas the Resource objects should be used
 * for queries that is meant to return more than one obejct.
 *
 * @author Michael Valentin <mv@signifly.com>
 */
class DataAccessObject {
	protected $queryBuilder;
	protected $table;
	protected $fields = array();
	protected $primaryKey = array();
	protected $fieldDefinitions = array();
	protected $modelDefinition = array();
	
	public function __construct() {
		$this->queryBuilder = new \Flyf\Database\QueryBuilder();
	}

	public function SetTable($table) {
		$this->table = $table;
	}

	public function SetFields($fields) {
		$this->fields = array_keys($fields);
		$this->fieldDefinitions = $fields;
	}
	
	public function SetModel($model){
		$this->modelDefinition = $model;
	}
	
	public function SetPrimaryKey(array $columns){
		$this->primaryKey = $columns;
	}

	/**
	 * What is the data of the first row that matches the supplied data?
	 *
	 * @param array $data An associative array of columns=>values)
	 * @return mixed The result as an associative array, false if no result is found
	 */
	public function Load(array $data) {
		if (is_array($data)) {
			$this->queryBuilder->SetType('select');
			$this->queryBuilder->SetTable($this->table);
			$this->queryBuilder->SetFields($this->fields);
			
			$this->queryBuilder->SetLimit(1);

			foreach ($data as $key => $value) {
				if(DEBUG && !in_array($key,$this->fields)) Debug::Hint('The field "'.$key.'" is not defined. This might be the reason that the Load method is returning unexpected results.');
				$this->queryBuilder->AddCondition('`'.$key.'` = :'.$key);
				$this->queryBuilder->BindParam($key, $value);
			}

			if (count($result = $this->queryBuilder->Execute())) {
				return $result[0];
			} else {
				return false;	
			}
		}else{
			throw new InvalidArgumentException("The input data for the Load method must be an array of fields to search for.");
		}
	}

	/**
	 * Insert/Update this data
	 * 
	 * @param array $data The data to insert/update with
	 * @param boolean $insert Is this an insert? If not it's treated as an update..
	 * @throws DangerousQueryException If trying to update without a fully defined primaryKey
	 * @return array The data of the 
	 */
	public function Save(array $data, $insert=false) {
		if (!$insert) {
			$this->queryBuilder->SetType('update');

			if(array_diff($this->primaryKey, array_keys($data))) throw new DangerousQueryException("It is not allowed to perform updates on more than one row via the Save method, so you should specify full primary key when updating."); 
						
			foreach ($this->primaryKey as $key) {
				$this->queryBuilder->AddCondition($key.' = :'.$key);
				$this->queryBuilder->BindParam($key, $data[$key]);
			}
		} else {
			$this->queryBuilder->SetType('insert');
		}
		
		$this->queryBuilder->SetTable($this->table);

		$this->queryBuilder->SetFields(array_keys($data));
		$this->queryBuilder->SetValues(array_values($data));
		
		$this->queryBuilder->SetLimit(1);

		//Try to give the InsertID back to through the data..
		$id = $this->queryBuilder->Execute();
		if ($id !== null && !isset($data['id'])) {
			$data['id'] = $id;
		}

		return $data;
	}

	/**
	 * Delete the record with this primaryKey
	 * 
	 * @param array $primaryKey The primary key of the record to delete
	 */
	public function Delete(array $primaryKey) {
		$data = $primaryKey;
		$this->queryBuilder->SetType('delete');
		$this->queryBuilder->SetTable($this->table);
		$this->queryBuilder->SetLimit(1);
		foreach($this->PrimaryKey as $field){
			$value = isset($data[$field]) ? $data[$field] : false;
			if(!$value)	throw new DangerousQueryException("To delete a entry, the FULL primaryKey must be specified.");
			$this->queryBuilder->AddCondtion('`'.$field.'` = :'.field);
			$this->queryBuilder->BindParam($field, $value);
		}
		$this->queryBuilder->Execute();
	}

	/**
	 * Does a record with this primaryKey exist in the database?
	 * 
	 * All parts of the primary key must be supplied, otherwise the method
	 * returns false. However, it is not necessary to supply all data from 
	 * the object, as only the primary key is used.
	 * 
	 * @param array $data The primaryKey including values to search for
	 * @return boolean True if the a record in the database with the given primaryKey exists. Otherwise false.
	 */
	public function Exists(array $primaryKey){
		$data = $primaryKey;
		$this->queryBuilder->SetType('select');
		$this->queryBuilder->SetTable($this->table);
		$this->queryBuilder->SetFields($this->PrimaryKey[0]);
		$this->queryBuilder->SetLimit(1);
		foreach($this->PrimaryKey as $field){
			$value = isset($data[$field]) ? $data[$field] : false;
			if(!$value)	return false;
			$this->queryBuilder->AddCondtion('`'.$field.'` = :'.field);
			$this->queryBuilder->BindParam($field, $value);
		}
		return count($this->queryBuilder->Execute()) > 0;
	}
}
