<?php
namespace Flyf\Util;

class LessCssParser {
	public static function Parse($input){
		require_once 'Flyf/External/lessphp/lessc.inc.php';
		$less = new \lessc();
		return $less->parse($input);
	}
}

?>