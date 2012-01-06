<?php
namespace Flyf\Util;

use \Flyf\Core\Config as Config;
use \Flyf\Core\Request as Request;
use \Flyf\Language\LanguageSettings as LanguageSettings;

// testblogmain:

class UrlHelper {
	private static $urlHelper;

	// definer standard params ud fra component
	// find og arrangerer params / component / s
	// match over til seo url via regex i rækkefølge

	private $urls = array(
		'testblogmain:action=view,id=(.+?)' => 'blog/$1',
		'testblogmain:action=(.+?),id=(.+?)' => 'blog/$2/$1'
	);

	public static function GetUrlHelper() {
		if (!self::$urlHelper) {
			if ($helper = Config::GetValue('url_helper')) {
				self::$urlHelper = $helper;
			} else {
				self::$urlHelper = new UrlHelper();
			}
		}

		return self::$urlHelper;
	}

	public function getProductUrl($pruduct) {
		return '/'.Translate('blog').'/'.$product->get('id');
	}

	public function GetUrl($key, $values = null) {
		if (!isset($this->urls[$key])) {
			throw new \Exception('The url key "'.$key.'" is not defined in the "'.__CLASS__.'"');
		}
		$url = $this->urls[$key];
		
		if (($language = Request::GetRequest()->GetLanguage()) != LanguageSettings::GetDefaultLanguage()) {
			$url = $language.'/'.$url;
		}

		$url = '/'.Config::GetValue('root_path').'/'.$url;
		
		if ($values && is_array($values)) {
			foreach ($values as $key => $value) {
				$url = str_replace($key, $value, $url);
			}
		}

		return $url;
	}

	private function CompleteUrl($url) {
		// ud fra det givne component, skal alle parents findes så url'en kan konstrueres korrekt
		return $url;
	}
}
?>
