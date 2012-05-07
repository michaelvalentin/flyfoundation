<?php
namespace Flyf;

use Flyf\Database\Connection;

class Database {
	
	/**
	 * Factory method to get a connection. The connection is taken
	 * from the current set of connections, by the index specified. Eg. to
	 * open a new connection, you must use a new index which has not
	 * been specified earlier. When opening a new connection you can supply
	 * options in order to connect to another database.
	 * 
	 * @param string $index (How should this connection be labeled for later use?)
	 * @param array $options (Connection options hostname, username, password, 
	 * database, charset and database_type. If not set, defaults (from config) are used)
	 */
	public static function GetConnection($index = "default", array $options = array()){
		return Connection::GetConnection($index,$options);
	}
	
	/**
	 * Utility method for simpler database calls. Prepare and execute the 
	 * given query, with the given parameters.
	 * 
	 * @param string $queryString
	 * @param array $bindParams
	 */
	public static function Query($queryString, array $bindParams = array()){
		$connection = self::GetConnection();
		$connection->Prepare($queryString);
		$connection->Bind($bindParams);
		return $connection->ExecuteQuery();	
	}
	
	/**
	 * Utility method for simpler database calls. Prepare and execute the
	 * given NONquery, with the given parameters.
	 *
	 * @param string $queryString
	 * @param array $bindParams
	 */
	public static function NonQuery($queryString, array $bindParams = array()){
		$connection = self::GetConnection();
		$connection->Prepare($queryString);
		$connection->Bind($bindParams);
		return $connection->ExecuteNonQuery();
	}
	
	public static function Select(){
		return new \Flyf\Database\Select();
	}
}

?>