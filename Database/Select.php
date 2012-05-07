<?php
namespace Flyf\Database;

use Database\Query;

class Select extends Query {
	const SELECT_ALL = "*";
	
	private $distinct = false;
	private $columns = array();
	
	public function __construct(\Flyf\Database\Connection $conn){
		parent::__construct($conn);
		$this->parts[] = "select";
		$this->parts[] = "columns";
	}
	
	public function Distinct(){
		$this->distinct = true;
	}
	
	public function Column($column,$alias = false){
		$alias = $alias ? $alias : $column;
		$this->columns[$alias] = $column;
	}
	
	public function Columns(array $columns, $aliases = false){
		foreach($columns as $alias=>$column){
			$this->Column($column,$aliases ? $alias : false);
		}
	}
	
	protected function render_select(){
		return "SELECT ".($this->distinct ? "DISTINCT ":"");
	}
	
	protected function render_columns(){
		if(count($this->columns == 0)) return "*";
		$output = "";
		$columns = array();
		foreach($this->columns as $alias=>$column){
			if($column!=self::SELECT_ALL){
				$columns[] = self::SELECT_ALL;
				continue;
			}
			$columns[] = $column." AS ".$alias;
		}
		return implode(", ",$columns)." ";
	}
}

?>