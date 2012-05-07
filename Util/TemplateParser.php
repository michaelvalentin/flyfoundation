<?php
namespace Flyf\Util;

use Flyf;

/**
 * A simple template parser based on the php mustache engine
 * 
 * @author Michael Valentin <mv@signifly.com>
 */
class TemplateParser {
	/**
	 * Render this template with this view
	 * 
	 * @param string $template (The input template)
	 * @param Flyf\View $view (The view to use when populating the template)
	 * @return string (The parsed template)
	 */
	public static function ParseTemplate($template, Flyf\Components\Abstracts\View $view){
		$options = array(
			       'pragmas' => array(
           				Mustache::PRAGMA_UNESCAPED => true
      				),
			      );
		$m = new Mustache(null,null,null,$options);
		return $m->render($template, $view->GetStringValues());
	}

	public static function BufferTemplate($template) {
		ob_start();
		require $template;
		return ob_get_clean();
	}
}

?>
