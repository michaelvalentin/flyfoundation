<?php
namespace Flyf\Database;

class TableBuilder {
	private $connection;
	private $fields;
	private $tableName;
	private $primaryKey;
	
	public function __construct($tableName){
		$this->tableName = $tableName;
		$this->connection = Connection::GetConnection();
		$this->fields = array();
		$this->primaryKey = array();
	}
	
	public function AddField($name, $type, $limit=false){
		$this->fields[$name] = array("type"=>$this->Convert($type, $limit));
	}
	
	public function CreateTable(){
		$query = "CREATE TABLE IF NOT EXISTS ".$this->tableName."(";
		$first = true;
		foreach($this->fields as $field=>$params){
			if(!$first) $query .= ", ";
			$query .= $field." ".$params["type"];
			if(isset($params["notNull"])) $query .= " NOT NULL";
			$first = false;
		}
		if(isset($this->primaryKey)){
			$query .= ", PRIMARY KEY (".implode(",", $this->primaryKey).")";
		}
		$query .= ");";
		$connection = Connection::GetConnection();
		$connection->Prepare($query);
		echo $query;
		$connection->ExecuteNonQuery();
	}
	
	public function SetNotNull($name){
		$this->fields[$name]["notNull"] = true;
	}
	
	public function SetPrimaryKey($name){
		if(array_key_exists($name,$this->fields))$this->primaryKey[] = $name;
	}
	
	private function Convert($className, $limit){
		$limText = $limit ? "(".$limit.")" : "";
		switch(strtolower($className)){
			case "boolean" :
				return "TINYINT(1)";
				break;
			case "string" :
				if($limit > 255) return "TEXT".$limText;
				return "VARCHAR".$limText;
				break;
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
}
?>
