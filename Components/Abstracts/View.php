<?php
namespace Flyf\Components\Abstracts;

use Flyf\Core\Config;

/**
 * The default view in Flyf. A collection of data and rules 
 * on how to format it. Can be parsed with a template to html.
 * 
 * @author Michael Valentin
 */
class View {
	private $values = array();
	
	/**
	 * Add value on this label
	 * 
	 * @param string $label
	 * @param mixed $value
	 */
	public function AddValue($label, $value){
		if(is_array($value) && false){
			$this->AddArrayValue($label,$value);
		}else{
			$this->values[$label] = $value;
		}
	}
	
	/**
	 * Add multiple values to the view
	 * 
	 * @param array $data (Array on the form $label=>$value)
	 */
	public function AddValues(array $data){
		foreach($data as $label=>$value){
			$this->AddValue($label, $value);
		}
	}
	
	/**
	 * Add an array of values
	 * 
	 * @param string $label
	 * @param array $array
	 */
	private function AddArrayValue($label, array $array){
		//$this->values[$label] = $array;
	}
	
	/**
	 * 
	 * @param array $array
	 */
	private function Process(array $values){
		$output = array();
		foreach($values as $l=>$v){
			if(is_array($v)){
				$output[$l] = $this->Process($v); //Work recursively through the array..
				continue;
			}
			if($v instanceof \Flyf\Models\Abstracts\RawModel){
				$output[$l] = $v->AsArray();
				continue;
			}
			if($v instanceof \Flyf\Util\HtmlString){
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
	
	public function GetStringValues(){
		return $this->Process($this->values); //Convert all values to strings before outputting.
	}
	
	public function GetValues(){
		return $this->values;
	}
}