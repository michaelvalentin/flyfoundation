<?php
namespace Flyf\Database;

use Flyf\Core\Config;

class SimpleQueries {
	/**
	 * Does the table exist in the database?
	 */
	public static function TableExists($table_name){
		$db = \Flyf\Database\Connection::GetConnection();
		$db->Prepare("SHOW TABLES LIKE :name");
		$db->Bind(array("name"=>$table_name));
		$res = $db->ExecuteQuery();
		if(count($res["result"]) > 0) return true;
		return false;
	}
	
	public static function ClearAll(){
		if(!DEBUG) return;
		$db = \Flyf\Database\Connection::GetConnection();
		$db->Prepare("DROP DATABASE :database");
		$db->Bind(array("database"=>Config::GetValue("database_database")));
		$db->ExecuteNonQuery();
		$db->Prepare("CREATE DATABASE :database");
		$db->Bind(array("database"=>Config::GetValue("database_database")));
		$db->ExecuteNonQuery();
	}
}

?>