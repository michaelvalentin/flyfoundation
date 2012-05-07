<?php
namespace Database;

use Flyf\Database;

abstract class Query {
	protected $connection;
	protected $bind;
	protected $parts;
	protected $columns;
	
	public function __construct(\Flyf\Database\Connection &$conn = null){
		if($conn == null) $conn = Database::GetConnection();
		$this->connection &= $conn;
		$this->bind = array();
		$this->parts = array();
	}
	
	public function End(){
		$this->connection->Prepare($this->Render());
		$this->connection->Bind($this->bind);
	}
	
	public function Render(){
		$query = "";
		foreach($this->parts as $part){
			$renderer = "render_".$part;
			$query .= $this->$renderer();
		}
		return $query;
	}
}

?>