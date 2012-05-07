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
		ob_start();
		foreach($files as $js){
			if(is_file($js)){
				if(DEBUG) echo "/* ".$js." */"."\n";
				require $js;
				echo "\n";
				if(DEBUG) echo "/* EOF ".$js." */"."\n"."\n";
			}else{
				Debug::Hint('Trying to load nonexisting javascript file: "'.$js.'"');
			}
		}
		$output = ob_get_clean();
		return $output;
	}
}

?>