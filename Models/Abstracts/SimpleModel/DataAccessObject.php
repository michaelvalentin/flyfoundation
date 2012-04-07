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
}
