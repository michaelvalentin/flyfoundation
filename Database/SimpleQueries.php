<?php
namespace Flyf\Database;

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
}

?>