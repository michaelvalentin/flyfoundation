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
	
	private function Process(array $array){
		$output = array();
		foreach($array as $l=>$v){
			if(is_array($v)){
				$output[$l] = $this->Process($v);
				continue;
			}
			if($v instanceof \Flyf\Models\Abstracts\Model){
				$output[$l] = $v->AsArray();
				continue;
			}
			if($v instanceof Util\HtmlString){
				//if(!$v->Validate()) Debug::Hint("Malformed html in".get_called_class()); //TODO: Implement
				$output[$l] = $v->Output();
				continue;
			}
			if(is_bool($v) || is_int($v)){
				$output[$l] = $v;
				continue;
			}
			if(is_float($v)){
				$output[$l] = \Flyf\Language\Formatter::FormatFloat($v);
				continue;
			}
			if(is_string($v)){
				$output[$l] = htmlentities($v);
				continue;
			}
			if($v instanceof \DateTime){
				$output[$l] = \Flyf\Language\Formatter::FormatDateTime($v);
				continue;
			}
			$output[$l] = (string) $v; //TODO: Consider a "Printable" interface instead??
		}
		return $output;
	}
	
	public function GetValues(){
		$this->values = $this->Process($this->values);
		return $this->values;
	}
}

?>
