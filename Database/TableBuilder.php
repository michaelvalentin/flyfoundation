<?php
namespace Flyf\Database;

use Flyf\Exceptions\InvalidAnnotationException;

use \Flyf\Util\Debug;

class TableBuilder {
	private $connection;
	private $tableName;
	private $fields;
	private $indexes;
	private $constraints;
	private $primaryKeys;
	
	public function __construct($tableName){
		$this->tableName = $tableName;
		$this->connection = Connection::GetConnection();
		$this->fields = array();
		$this->indexes = array();
		$this->constraints = array();
		$this->primaryKeys = array();
	}
	
	public function AddField($name, $options){
		$this->fields[$name] = $options;
	}
	
	public function AddConstraint($options){
		$this->constraints[] = $options;
	}
	
	public function AddIndex($name, $options){
		$this->indexes[$name] = $options;
	}
	
	public function CreateTable(){
		$columns = $this->getColumns();
		$indexes = $this->getIndexes();
		$constraints = $this->GetConstraints();
		
		//Add a primary key if available...
		if(count($this->primaryKeys)) $constraints[] = "PRIMARY KEY (".implode(",", $this->primaryKeys).")";
		
		//Build the query and create the table
		$query = "CREATE TABLE IF NOT EXISTS ".$this->tableName."(";
		$query .= implode(" , ",array_merge($columns,$constraints,$indexes));
		$query .= ");";
		echo "#####".$query."#####";
		$connection = Connection::GetConnection();
		$connection->Prepare($query);
		$connection->ExecuteNonQuery();
	}
	
	private function GetColumns(){
		$columns = array();
		foreach($this->fields as $column => $options){
			//Create the column
			$output = "";
			$output .= $column;
			$output .= " ".$this->ConvertType($options["type"],$options["maxLength"]);
			if($options["required"]) $output .= " NOT NULL";
			if($options["default"]) $output .= " DEFAULT '".$options["default"]."'";
			if($options["autoIncrement"]) $output .= " AUTO_INCREMENT";
			if($options["unique"]) $output .= " UNIQUE";
			if($options["primaryKey"]) $this->primaryKeys[] = $column;
			$columns[] = $output;
			
			//Setup references
			if($options["reference"] && $options["reference_column"]){
				$table = $options["reference"]->GetTable();
				$reference = "CONSTRAINT FOREIGN KEY ".$column."_rel (".$column.") REFERENCES ".$table." (".$options["reference_column"].")";
				if(in_array($options["reference_match"],array("MATCH FULL","MATCH PARTIAL","MATCH SIMPEL"))){
					$reference .= " ".$options["reference_match"];
				}elseif($options["reference_match"]){
					Debug::Hint("Illegal reference_table \"".$options["reference_match"]."\" for \"".$column."\" in table \"".$this->tableName."\".");
				}
				$refoptions = array("RESTRICT","CASCADE","SET NULL","NO ACTION");
				if(!in_array($options["reference_on_delete"],$refoptions)){
					Debug::Error("Illegal reference_on_delete \"".$options["reference_on_delete"]."\" for \"".$column."\" in table \"".$this->tableName."\".");
					continue;
				}
				if(!in_array($options["reference_on_update"],$refoptions)){
					Debug::Error("Illegal reference_on_update \"".$options["reference_on_update"]."\" for \"".$column."\" in table \"".$this->tableName."\".");
					continue;
				}
				$reference .= " ON DELETE ".$options["reference_on_delete"];
				$reference .= " ON UPDATE ".$options["reference_on_update"];
				$columns[] = $reference;
			}
		}
		return $columns;
	}
	
	private function GetIndexes(){
		$indexes = array();
		foreach($this->indexes as $indexNumber=>$options){
			$indexName = $options["name"];
			//Verify index and continue if not valid..
			$allowed = array(
					"index"=>array(
							"INDEX",
							"KEY",
							"UNIQUE INDEX",
							"UNIQUE KEY"
							),
					"type"=>array(
							"BTREE",
							"HASH",
							),
					"fulltext"=>array(
							"FULLTEXT",
							"SPATIAL"
							)
					);
			foreach($allowed as $la=>$va){
				if($options[$la] && !in_array($options[$la],$va)){
					Debug::Error("Malformed index declaration when creating table \"".$this->tableName."\". The option \"".$la."\" can't be \"".$options[$la]."\" for the index ".$indexName);
					continue;
				}
			}
			if(!$options["type"]){
				Debug::Error("Malformed index declaration when creating table \"".$this->tableName."\". Index property can't be false in ".$indexName);
			}
			if(!count($options["columns"])){
				Debug::Error("Malformed index declaration when creating table \"".$this->tableName."\". One or more tables must defined when creating the index ".$indexName);
				continue;
			}
			
			//Make the index
			$index = "";
			if($options["fulltext"]) $index .= $options["fulltext"];
			$index .= " ".$options["index"];
			if(strlen($indexName)<3) throw new InvalidAnnotationException("Name of index should be at least 3 chars for index: ".$indexName);
			$index .= " ".$indexName;
			if($options["type"]) $index .= " ".$options["type"];
			$index .= " (".implode(",",$options["columns"]).")";
			$indexes[] = $index;
		}
		return $indexes;
	}
	
	private function GetConstraints(){
		$constraints = array();
		foreach($this->constraints as $v){
			//Continue if constraint isn't valid
			if(!in_array($v["type"],array("unique","reference"))){
				Debug::Error("Malformed constraint when building table \"".$this->tableName."\". Constraint type is not available.");
				continue;
			}
			if(!$v["name"]){
				Debug::Error("Malformed constraint when building table \"".$this->tableName."\". Constraint must have a name.");
				continue;
			}
			if(!is_array($v["columns"]) && !count($v["columns"])){
				Debug::Error("Malformed constraint when building table \"".$this->tableName."\". Constraint must apply to one or more columns");
				continue;
			}
				
			//Build the constraint
			$constraint = "";
			switch($v["type"]){
				case "unique" :
					$constraint .= "CONSTRAINT UNIQUE ".$v["name"];
					break;
				case "reference" :
					$constraint .= "CONSTRAINT FOREIGN KEY ".$v["name"];
					break;
			}
			$constraint .= "(".implode(",",$v["columns"]).")";
			if($v["type"]=="reference"){
				//Continue if the reference statement isn't valid...
				if(!$v["reference_table"]){
					Debug::Error("Malformed constraint when building table \"".$this->tableName."\". When setting up a reference, a reference_table must be declared.");
					continue;
				}
				if(count($v["reference_columns"]) != count($v["columns"])){
					Debug::Error("Malformed constraint when building table \"".$this->tableName."\". When setting up a reference, a the number of reference_columns must match the number of columns in the foreign key.");
					continue;
				}
				$refOptions = array(
						"RESTRICT",
						"CASCADE",
						"SET NULL",
						"NO ACTION"
				);
				$refAcceptTypes = array(
						"reference_match"=>array(
								"MATCH FULL",
								"MATCH PARTIAL",
								"MATCH MATCH SIMPEL"),
						"reference_on_delete"=>$refOptions,
						"reference_on_update"=>$refOptions
				);
				foreach($refAcceptTypes as $la=>$va){
					if($v[$la] && !in_array($v[$la],$va)){
						Debug::Error("Malformed constraint when building table \"".$this->tableName."\". The option \"".$la."\" was set to \"".$v[$la]."\" which is not a valid value.");
						continue;
					}
				}
		
				//Build the reference statement
				$constraint .= "REFERENCES ".$v["reference_table"]." (".implode(",",$v["reference_columns"]).")";
				if($v["reference_match"]) $constraint .= " ".$v["reference_match"];
				if($v["reference_on_delete"]) $constraint .= " ON DELETE ".$v["reference_on_delete"];
				if($v["reference_on_update"]) $constraint .= " ON UPDATE ".$v["reference_on_update"];
			}
			$constraints[] = $constraints;
		}
		return $constraints;
	}
	
	private function ConvertType($type, $limit){
		$limText = $limit ? "(".$limit.")" : "";
		switch(strtolower($type)){
			case "boolean" :
				return "TINYINT(1)";
				break;
			case "string" :
				if($limit && $limit < 255) return "VARCHAR(".$limit.")";
				return "TEXT".$limText;
				break;
			case "varchar" :
				if(!$limit || $limit > 255) $limText = "(255)";
				return "VARCHAR".$limText;
			case "integer" :
				return "INT".$limText;
				break;
			case "datetime" :
				return "DATETIME";
			default :
				throw new \Flyf\Exceptions\InvalidArgumentException('Unexpected className "'.$type.'" for conversion to DatabaseType');
				break;
		}
	}

	/**
	 * Build a database table for this model
	 * 
	 * @param string $table The name of the table to build
	 * @param \Flyf\Models\Abstracts\RawModel\ValueObject $valueObject The ValueObject to build the table from
	 */
	public static function BuildFromModel(\Flyf\Models\Abstracts\RawModel $model){
		if(!DEBUG) return; //Can only be used in debug mode!
		$class = get_called_class();
		$table = new $class($model->GetTable());
		$valueObject = $model->GetEmptyValueObject();
		foreach($valueObject->GetFieldDefinitions() as $column=>$options){
			$table->AddField($column, $options);
		}
		foreach($valueObject->GetModelProperties() as $column=>$options){
			if($options["index"]){
				$table->AddIndex($column,$options);
			}elseif($options["constraint"]){
				$table->AddConstraint($options);
			}
		}
		$table->CreateTable();
	}
}