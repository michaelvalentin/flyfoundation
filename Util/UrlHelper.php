<?php
namespace Flyf\Util;

use \Flyf\Core\Config as Config;
use \Flyf\Core\Request as Request;

class UrlHelper {
	private static $urlHelper;

	public static function GetUrlHelper() {
		if (!self::$urlHelper) {
			if ($helper = Config::GetValue('url_helper')) {
				self::$urlHelper = $helper;
			} else {
				self::$urlHelper = new UrlHelper();
			}
		}
	}

	public function GetUrl($key) {
		$request = Request::GetRequest();
	}

	private function CompleteUrl($url) {
		return $url;
	}

	private function ReverseUrl($url) {
		return $url;
	}
}
?>
