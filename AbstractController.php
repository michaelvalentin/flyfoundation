<?php
namespace Flyf;

abstract class AbstractController {
	protected $_view;
	protected $_template;
	protected $_controllers;
	protected $_cache = 0;
	protected static $_params = array();

	public function __construct($params = array()) {
		self::$_params = array_merge(self::$_params, $params);

		$prefix = str_replace('Controller', '', end(explode('\\', get_class($this))));
		if (($nextComponent = \Flyf\Util\ComponentLoader::NextComponent($this)) !== null) {
			$this->_controllers[$prefix.'Content'] = $nextComponent;
		}
	}
	
	public function Process(){ //TODO: Implement caching...
		if(is_array($this->_controllers)){
			foreach($this->_controllers as $controller) {
		
				$controller->Process();
			}
		}
	}
	
	public function CollectData(){ //TODO: Implement caching...
		if(is_array($this->_controllers)){
			foreach($this->_controllers as $controller){
				$controller->CollectData();
			}
		}
	}
	
	public function Render(){ //TODO: Implement caching...
		if(is_array($this->_controllers)){
			foreach($this->_controllers as $l=>$controller){
				$this->_view->AddValue($l,new \Flyf\Util\HtmlString($controller->Render(),array("all")));
			}
		}
		return Util\TemplateParser::ParseTemplate($this->_template,$this->_view);
	}

	public static function FormatSeoParameters($parameters) {
		return implode('/', $parameters).(count($parameters) > 0 ? '/' : '');
	}

	public static function FormatSystemParameters($parameters) {
		$result = '';
		
		if ($count = count($parameters)) {
			$result .= '(';

			$x = 1;
			foreach ($parameters as $key => $value) {
				$result .= $key.'='.$value.($count > $x ? '&' : '');

				$x++;
			}

			$result .= ')';
		}

		return $result;
	}
}

?>
