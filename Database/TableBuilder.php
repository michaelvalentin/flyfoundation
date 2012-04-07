<?php
namespace Flyf\Database;

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
		echo $query;
		$connection = Connection::GetConnection();
		$connection->Prepare($query);
		$connection->ExecuteNonQuery();
	}
	
	private function GetColumns(){
		$columns = array();
		foreach($this->fields as $l => $v){
			//Create the column
			$column = "";
			$column .= $l;
			$column .= " ".$this->Convert($v["type"],$v["maxLength"]);
			if($v["required"]) $column .= " NOT NULL";
			if($v["default"]) $column .= " DEFAULT '".$v["default"]."'";
			if($v["autoIncrement"]) $column .= " AUTO_INCREMENT";
			if($v["unique"]) $column .= " UNIQUE";
			if($v["primaryIndex"]) $this->primaryKeys[] = $l;
			$columns[] = $column;
			
			//Setup references
			if($v["reference"] && $v["reference_column"]){
				$table = $v["reference"]->GetTable();
				$reference = "COSNTRAINT FOREIGN KEY ".$l."-rel (".$l.") REFERENCES ".$table." (".$v["reference_column"].")";
				if(in_array($v["reference_table"],array("MATCH FULL","MATCH PARTIAL","MATCH SIMPEL"))){
					$reference .= " ".$v["reference_match"];
				}elseif($v["reference_match"]){
					Debug::Hint("Illegal reference_table \"".$v["reference_match"]."\" for \"".$l."\" in table \"".$this->tableName."\".");
				}
				$refoptions = array("RESTRICT","CASCADE","SET NULL","NO ACTION");
				if(!in_array($v["reference_on_delete"],$refoptions)){
					Debug::Error("Illegal reference_on_delete \"".$v["reference_on_delete"]."\" for \"".$l."\" in table \"".$this->tableName."\".");
					continue;
				}
				if(!in_array($v["reference_on_update"],$refoptions)){
					Debug::Error("Illegal reference_on_update \"".$v["reference_on_update"]."\" for \"".$l."\" in table \"".$this->tableName."\".");
					continue;
				}
				$reference .= " ".$v["reference_on_delete"];
				$reference .= " ".$v["reference_on_update"];
				$columns[] = $reference;
			}
		}
		return $columns;
	}
	
	private function GetIndexes(){
		$indexes = array();
		foreach($this->indexes as $l=>$v){
			//Verify index and continue if not valid..
			$allowed = array("
					index"=>array(
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
				if($v[$la] && !in_array($v[$la],$va)){
					Debug::Error("Malformed index declaration when creating table \"".$this->tableName."\". The option \"".$la."\" can't be \"".$v[$la]."\" for the index ".$l);
					continue;
				}
			}
			if(!$v["type"]){
				Debug::Error("Malformed index declaration when creating table \"".$this->tableName."\". Index property can't be false in ".$l);
			}
			if(!count($v["columns"])){
				Debug::Error("Malformed index declaration when creating table \"".$this->tableName."\". One or more tables must defined when creating the index ".$l);
				continue;
			}
			
			//Make the index
			if($v["fulltext"]) $index = $v["fulltext"];
			$index .= " ".$v["index"];
			$index .= " ".$l;
			if($v["type"]) $index .= " ".$v["type"];
			$index .= " (".implode(",",$v["columns"]).")";
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
	
	private function Convert($className, $limit){
		$limText = $limit ? "(".$limit.")" : "";
		switch(strtolower($className)){
			case "boolean" :
				return "TINYINT(1)";
				break;
			case "string" :
				if($limit < 255 && $limit) return "VARCHAR".$limText;
				return "TEXT".$limText;
				break;
			case "varchar" :
				if(!$limit || $limit > 255) $limit = 255;
				return "VARCHAR".$limText;
			case "integer" :
				return "INT".$limText;
				break;
			case "datetime" :
				return "DATETIME";
			default :
				throw new \Flyf\Exceptions\InvalidArgumentException("Unexpected className for conversion to DatabaseType");
				break;
		}
	}

	/**
	 * Build a database table for this model
	 * 
	 * @param \Flyf\Models\Abstracts\RawModel $model
	 */
	public static function BuildFromModel(\Flyf\Models\Abstracts\RawModel $model){
		if(!DEBUG) return; //Can only be used in debug mode!
		$class = get_called_class();
		$table = new $class($model->GetTable());
		$vo = $model->GetValueObject();
		foreach($vo->GetFieldDefinitions() as $column=>$options){
			$table->AddField($column, $options);
		}
		foreach($vo->GetModelDefinition() as $column=>$options){
			if($options["index"]){
				$table->AddIndex($column,$options);
			}elseif($options["constraint"]){
				$table->AddConstraint($options);
			}
		}
		$table->CreateTable();
	}
}