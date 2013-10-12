<?php
namespace Flyf\Database;

use Flyf\Exceptions\DangerousQueryException;
use Flyf\Core\Config;
use Flyf\Database;

/**
 * Simple standard queries, to be performed on the database.
 * The class wraps the queries and makes them easier to call,
 * without worries about SQL-syntax.
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
class SimpleQueries {
	/**
	 * Does a table with this name exist in the database?
	 * 
	 * @param string $table_name (The name of the table to look for)
	 * @return boolean (True if a table with the specified name exists. Else false)
	 */
	public static function TableExists($table_name){
		$db = \Flyf\Database\Connection::GetConnection();
		$db->Prepare("SHOW TABLES LIKE :name");
		$db->Bind(array(":name"=>$table_name));
		$res = $db->ExecuteQuery();
		if(count($res["result"]) > 0) return true;
		return false;
	}
	
	/**
	 * Delete all tables in the database...
	 * 
	 * @throws DangerousQueryException (If run in production -> Only allowed in debug mode!)
	 */
	public static function DeleteAllTables(){
		if(!DEBUG) throw new DangerousQueryException("Database can ONLY be cleared in debug mode!!!");
		$db = \Flyf\Database\Connection::GetConnection();
		$dbname = Config::GetValue("database_database");
		$db->DoTransaction(function()use(&$db,$dbname){
			$db->Prepare("DROP DATABASE ".$dbname);
			$db->ExecuteNonQuery();
			$db->Prepare("CREATE DATABASE ".$dbname."; USE ".$dbname.";");
			$db->ExecuteNonQuery();
		});
	}
}

?>