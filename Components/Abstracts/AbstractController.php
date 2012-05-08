<?php
namespace Flyf\Components\Abstracts;

use Flyf\Exceptions\InvalidArgumentException;

use Flyf\Core\Config;

use \Flyf\Core\Response as Response;

abstract class AbstractController {
	protected $_view;
	protected $_template;
	protected $_params;
	protected $_layout;

	/**
	 * Make a new controller with these parameters
	 * 
	 * @param array $params
	 */
	public function __construct(array $params = array()) {
		$this->_params = $params;
		$this->_view = $this->FindView();
		$this->_template = '<p>NO TEMPLATE DEFINED IN "'.get_called_class().'"</p>';
	}
	
	/**
	 * Find the nearest view in the inheritance chain
	 * 
	 * @return \Flyf\Components\Abstracts\View
	 */
	private function findView(){
		$view = null;
		$class = get_called_class();
		//Step backward the inheritance-chain until a View is found...
		while($view==null && $class){
			$classParts = explode("\\",$class);
			array_pop($classParts);
			$viewClass = implode("\\",$classParts)."\\View";
			if(class_exists($viewClass))
			{ 
				$view = new $viewClass(); 
			}
			else
			{
				$class = get_parent_class($class);
			}
		}
		return $view;
	}
	
	/**
	 * Set the layout to wrap this controller in
	 * 
	 * @param \Flyf\Components\Abstracts\AbstractLayout $layout
	 */
	protected function setLayout(AbstractLayout $layout){
		$this->_layout = $layout;
	}
	
	/**
	 * Collect the necessary data and populate the View class
	 */
	protected abstract function collectData();
	
	/**
	 * Prepare the controller, by setting title, scripts
	 * css, layout, etc.
	 */
	protected abstract function prepare();
	
	/**
	 * Select a template for this controller
	 */
	protected abstract function selectTemplate();

	/**
	 * Select modules for positions in the layout
	 */
	protected function selectLayoutModules(){
		
	}
	
	public function Render(){
		$this->collectData();
		$this->prepare();
		$this->selectTemplate();
		$output = \Flyf\Util\TemplateParser::ParseTemplate($this->_template, $this->_view);
		if($this->_layout){
			$this->selectLayoutModules();
			$output = $this->_layout->WrapContent($output);
		}
		return $output;
	}
	
	protected function AddJs($script, $folder = false){
		$files = $this->GetPossibleFiles($script, $folder, "js");
		foreach($files as $file){
			if(is_file($file)){
				$response = \Flyf\Core\Response::GetResponse();
				$response->AddJs($file);
				return;
			}
		}
		throw new InvalidArgumentException('None of the files "'.implode(" or ",$files).'" exists, and can\'t be included as js.."');
	}
	
	protected function AddCss($css, $folder = false){
		$files = $this->GetPossibleFiles($css, $folder, "css");
		foreach($files as $file){
			if(is_file($file)){
				$response = \Flyf\Core\Response::GetResponse();
				$response->AddCss($file);
				return;
			}
		}
		throw new InvalidArgumentException('None of the files "'.implode(" or ",$files).'" exists, and can\'t be included as css.."');
	}
		
	private function GetPossibleFiles($file, $folder, $subfolder){
		if(!$folder){
			$class = get_called_class();
			$parts = explode("\\",$class);
			array_shift($parts);
			array_pop($parts);
			$folder = implode(DS,$parts).DS.$subfolder;
		}
		$options = array();
		$options[] = LOCAL_ROOT.DS.$folder.DS.$file;
		$options[] = FLYF_ROOT.DS.$folder.DS.$file;
		return $options;
	}
}

?>
