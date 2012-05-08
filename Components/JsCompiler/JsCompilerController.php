<?php
namespace Flyf\Components\JsCompiler;

use Flyf\Util\Debug;

use Flyf\Components\Abstracts\AbstractController;

class JsCompilerController extends AbstractController {
	protected function collectData() {
	}
	
	protected function prepare() {
	}
	
	protected function selectTemplate() {
	}
	
	public function Render(){
		\Flyf\Core\Response::GetResponse()->SetContentType("text/javascript");
		$list = isset($this->_params["in"]) ? urldecode(base64_decode($this->_params["in"])) : "";
		$files = explode(",",$list);
		$output = "";
		foreach($files as $js){
			if(is_file($js) && preg_match("/\.js$/",$js)){
				if(DEBUG) $output .= "/* ".$js." */"."\n";
				$output .= file_get_contents($js);
				$output .= "\n";
				if(DEBUG) $output .= "/* EOF ".$js." */"."\n"."\n";
			}else{
				Debug::Hint('Trying to load nonexisting javascript file: "'.$js.'". File extension must be .js');
			}
		}
		return $output;
	}
}

?>