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
				$output .= \Flyf\Util\LessCssParser::Parse($less);
			}else{
				\Flyf\Util\Debug::Hint('Trying to load nonexisting css file: "'.$css.'"');
			}
		}
		return $output;
	}	
}

?>