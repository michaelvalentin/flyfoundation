<?php
namespace Flyf\Util;

use \Flyf\Core\Config as Config;
use \Flyf\Core\Request as Request;
use \Flyf\Util\Debug as Debug;
use \Flyf\Language\LanguageSettings as LanguageSettings;
use \Flyf\Models\Url\Rewrite as Rewrite;

class UrlHelper {

	private static $seo = array(
		'blok18/secure/blog/page' => 'blog'
	);
	
	public static function GetUrl($url, $language = 'current', $absolute = false, $secure = false) {
		Debug::Log('UrlHelper::GetUrl input: '.$url);
		
		$rewriteSeo = '';
		$rewriteSystem = '';

		$request = Request::GetRequest();

		$components = array();
		$parameters = array();
		$fragments = explode('/', $url);

		foreach ($fragments as $fragment) {
			$component = explode('(', $fragment);
			
			$components[] = $component[0];

			if (count($component) > 1) {
				$params = str_replace(')', '', $component[1]);
				$params = explode('&', $params);

				foreach ($params as $param) {
					$split = explode('=', $param);
					$key = $split[0];
					$value = $split[1];

					$parameters[$component[0]][$key] = $value; 
				}
			}
		}

		$targetComponent = $component[0];
		$targetParameters = $parameters[$targetComponent];
		
		if (!$absolute) {
			$currentComponents = $request->GetComponents();
			array_pop($currentComponents);
			
			$components = array_merge($currentComponents, $components);
		}
		
		foreach ($components as $key => $component) {
			if ($component != $targetComponent) { 
				$params = isset($parameters[$component]) ? $parameters[$component] : $request->GetParams($component);

				$rewriteSeo .= $component.'/'.self::FormatSeoParameters($component, $params);
				$rewriteSystem .= $component.self::FormatSystemParameters($component, $params).'/';
			} else {
				$rewriteSeo .= $component;
				$rewriteSystem .= $component;
			}
		}

		Debug::Log('Rewrite Seo: '.$rewriteSeo);
		Debug::Log('Rewrite System: '.$rewriteSystem);
		
		$rewriteSeo = self::SubstituteSeo($rewriteSeo);
		
		$rewriteSeo .= '/'.self::FormatSeoParameters($targetComponent, $targetParameters);
		$rewriteSystem .= self::FormatSystemParameters($targetComponent, $targetParameters);

		// TODO domain fra database
		$rewriteSeo = 'www.blok18.dk/'.$rewriteSeo;
		$rewriteSystem = 'www.blok18.dk/'.$rewriteSystem;

		$rewriteSeo = $secure ? 'https://'.$rewriteSeo : 'http://'.$rewriteSeo;
		$rewriteSystem = $secure ? 'https://'.$rewriteSystem : 'http://'.$rewriteSystem;
		
		Debug::Log('Rewrite Seo: '.$rewriteSeo);
		Debug::Log('Rewrite System: '.$rewriteSystem);

		self::StoreRewrites($rewriteSeo, $rewriteSystem);
		
		return $rewriteSeo;
	}

	private static function SubstituteSeo($key) {
		if (isset(self::$seo[$key])) {
			return self::$seo[$key];
		} else {
			throw new \Exception('The key "'.$key.'" does not exists in the seo-table in UrlHelper');
		}
	}

	private static function FormatSeoParameters($component, $parameters) {
		// skal kaldes på components controller
		return implode('/', $parameters).(count($parameters) > 0 ? '/' : '');
	}

	private static function FormatSystemParameters($component, $parameters) {
		// skal kaldes på components controller
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

	private static function StoreRewrites($rewriteSeo, $rewriteSystem) {
		$rewrite = Rewrite::Load(array(
			'system' => $rewriteSystem
		));

		if ($rewrite->Exists()) {
			if ($rewrite->Get('seo') != $rewriteSeo) {
				// husk at føre den over i 301 table
				
				$rewrite->Set('seo', $rewriteSeo);
				$rewrite->Save();
			}
		} else {
			$rewrite = Rewrite::Create(array(
				'system' => $rewriteSystem,
				'seo' => $rewriteSeo
			));
			$rewrite->Save();
		}
	}

	/*
	private static $urlHelper;
	
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
	
	// definer standard params ud fra component
	// find og arrangerer params / component / s
	// match over til seo url via regex i rækkefølge

	private $urls = array(
		'testblogmain:action=view,id=(.+?)' => 'blog/$1',
		'testblogmain:action=(.+?),id=(.+?)' => 'blog/$2/$1'
	);
	*/

	/*
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
	*/
}
?>
