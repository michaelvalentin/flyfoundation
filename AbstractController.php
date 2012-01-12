<?php
namespace Flyf;

use \Flyf\Core\Response as Response;

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

		$class = get_called_class();		
		$class::AddIncludes();
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

	/**
	 * Dummy method for the system to call, if 
	 * the method has not been implemented by a child class.
	 */
	public static function AddIncludes() { }

	/** 
	 * Proxy method for Response->AddCss(), when
	 * using this method instead of the Response
	 * class method, the controller will determine
	 * where the css should be grabbed from (from
	 * the component itself, from another component
	 * or from an external source).
	 *
	 * 
	 * @param string $css
	 */
	public static function AddCss($css) {
		$class_pieces = explode('\\', get_called_class());
		$component = end(array_splice($class_pieces, -2, 1));

		if (stripos($css, 'http') !== 0 && stripos($css, 'components') !== 0) {
			$css = 'Components/'.$component.'/css/'.$css;
		}
		
		Response::GetResponse()->AddCss($css);
	}

	/** 
	 * Proxy method for Response->AddJs(), when
	 * using this method instead of the Response
	 * class method, the controller will determine
	 * where the css js be grabbed from (from
	 * the component itself, from another component
	 * or from an external source).
	 *
	 * 
	 * @param string $js
	 */
	public static function AddJs($js) {
		$class_pieces = explode('\\', get_called_class());
		$component = end(array_splice($class_pieces, -2, 1));

		if (stripos($js, 'http') !== 0 && stripos($js, 'components') !== 0) {
			$js = 'Components/'.$component.'/js/'.$js;
		}
		
		Response::GetResponse()->AddJs($js);
	}
}

?>
