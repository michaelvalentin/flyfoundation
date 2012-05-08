<?php
namespace Flyf\Components\CssCompiler;

use Flyf\Components\Abstracts\AbstractController;

class CssCompilerController extends AbstractController {
	protected function collectData() {
	}

	protected function prepare() {
	}

	protected function selectTemplate() {
	}

	public function Render(){
		\Flyf\Core\Response::GetResponse()->SetContentType("text/css");
		$list = isset($this->_params["in"]) ? urldecode(base64_decode($this->_params["in"])) : "";
		$files = explode(",",$list);
		$output = "";
		foreach($files as $css){
			if(is_file($css)){
				ob_start();
					require $css;
				$less = ob_get_clean();
				try{
					$output .= \Flyf\Util\LessCssParser::Parse($less);
				}
				catch(\Exception $ex){
					$output .= "/* file: ".$css." had a parsing-error.*/";
					\Flyf\Util\Debug::Hint('Css-file: "'.$css.'" couldn\'t be parsed as .less file, and was ignored.');
				}
			}else{
				\Flyf\Util\Debug::Hint('Trying to load nonexisting css file: "'.$css.'"');
			}
		}
		return $output;
	}	
}

?>