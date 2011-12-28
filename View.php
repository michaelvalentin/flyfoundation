<?php
namespace Flyf;

use Flyf\Core\Config;

/**
 * The default view in Flyf. A collection of data and rules 
 * on how to format it. Can be parsed with a template to html.
 * @author MV
 */
class View {
	private $values = array();
	
	/**
	 * Add value on this label
	 * @param string $label
	 * @param mixed $value
	 */
	public function AddValue($label, $value){
		$this->values[$label] = $value;
	}
	
	/**
	 * Add an array of values
	 * @param string $label
	 * @param array $array
	 */
	public function AddArray($label, array $array){
		array_merge($this->values,array($label=>$array));
	}
	
	protected function Process(){
		//Should escape values!!! - Wrap includes (that should not be escaped... in some sort of object indicating that it is "includedHtml")...
		//Should do standard formatting of Dates and the like...
		//Make an interface "printable" to allow the printing of.. Say a "Price" object..
	}
	
	public function GetValues(){
		$this->Process();
		return $this->values;
	}
}

?>