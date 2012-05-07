<?phpnamespace Flyf\Database;use Flyf\Util\Debug;use \Flyf\Core\Config;/** * Class to represent a database connection. Also handles * the factory method for creating connections, to make sure * that the fewest possible number of connections are made. *  * @author Michael Valentin <mv@signifly.com> */class Connection {	private $hostname;	private $username;	private $password;	private $database;	private $charset;	private $databaseType;		private static $connections;		private $pdo;	private $statement;	/**	 * Private constructor, to enforce the use of the factory method.	 */	private function __construct() {		$this->hostname = Config::GetValue("database_hostname");		$this->username = Config::GetValue("database_username");		$this->password = Config::GetValue("database_password");		$this->database = Config::GetValue("database_database");		$this->charset = Config::GetValue("database_charset");		$this->database_type = Config::GetValue("database_type");	}		/**	 * Factory method to get a connection. The connection is taken	 * from the current set of connections, by the index specified. Eg. to	 * open a new connection, you must use a new index which has not	 * been specified earlier. When opening a new connection you can supply	 * options in order to connect to another database.	 *  	 * @param string $index (The index for the requested connection)	 * @param array $options (The options for the request connection)	 */	public static function GetConnection($index = "default", array $options = array()){		if(!is_array(self::$connections))
		{
			self::$connections = array();
		}
		
		if(!isset(self::$connections[$index]))
		{
			$connection = new Connection();
			foreach($options as $label=>$option)			{
				$connection->$label = $option;
			}
			self::$connections[$index] = $connection;
		}		elseif(count($options))		{			Debug::Hint("Options are ignored, as the connection is allready initialized.");		}		
		$returnConnection = self::$connections[$index];
		$returnConnection->Connect();		
		return $returnConnection;	}		/**	 * Connect to the database	 * 	 * Setup the connection. This is called automatically by the	 * GetConnection method, so it is usually not necessary to	 * call this function in your code.	 */	public function Connect() {		if($this->pdo == null){			$connectionString = $this->database_type.':';			$connectionString .= 'host='.$this->hostname.';';			$connectionString .= 'dbname='.$this->database.';';			$this->pdo = new \PDO(				$connectionString, 				$this->username, 				$this->password,				array(						\PDO::ATTR_PERSISTENT => true				)			);						$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);			$this->pdo->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_NATURAL);		}	}	/**	 * Disconnect from the database	 */	public function Disconnect() {		$this->pdo = null;		$this->statement = null;	}		/**
	 * Perform these operations within a transaction, rolling
	 * back the database operations if any operations are
	 * unsuccessful.
	 *
	 * @param callable $function The operations to do, as an anonymous function
	 */
	public function DoTransaction(\Closure $function) {
		try
		{
			$this->pdo->beginTransaction();
			$function();
			$this->pdo->commit();
		}
		catch(\Exception $ex)
		{
			$this->pdo->rollBack();
			throw $ex;
		}
	}
	
	/**
	 * Prepare this query
	 *
	 * Prepare the supplied query, ready for binding of parameters
	 *
	 * @param String $query The query to prepare
	 */
	public function Prepare($query) {
		$this->statement = $this->pdo->prepare($query);
	}
	
	/**
	 * Execute the current query
	 */
	public function ExecuteQuery() {
		$this->statement->execute();
	
		return array(
				'result' => $this->statement->fetchAll(\PDO::FETCH_ASSOC),
				'last_insert_id' => 0,
				'row_count' => $this->statement->rowCount(),
		);
	}
	
	/**
	 * Execute a query that does not return data
	 */
	public function ExecuteNonQuery() {
		$var = $this->statement->queryString;
	
		if(DEBUG){
			try{
				$this->statement->execute();
			}
			catch(\Exception $ex){
				throw new \Exception("Query ### ".$this->statement->queryString." ### failed. ".$ex);
			}
		}else{
			$this->statement->execute();
		}
		return array(
				'result' => array(),
				'last_insert_id' => $this->pdo->lastInsertID(),
				'row_count' => $this->statement->rowCount(),
		);
	}
	
	/**
	 * Bind these parameters to the currently prepared statement
	 *
	 * @param array $params The parameters to bind
	 */
	public function Bind(array $params) {
		foreach ($params as $key => $value) {
			$this->statement->bindValue($key, $value);
		}
	}			/**	 * Execute the current query and return the first value of 	 * the first returned row	 */	public function LoadResult(){		$res = $this->ExecuteQuery();		$res = array_values($res['result'][0]);		return $res[0];	}		/**	 * Execute the current query and return the first row in the result 	 * set as an associative array	 */	public function LoadRow(){		$res = $this->ExecuteQuery();		return $res['result'][0];	}		/**	 * Execute the current query and return all rows in the result set	 * as an array of associative arrays 	 */	public function LoadRows(){		$res = $this->ExecuteQuery();		return $res['result'];	}}