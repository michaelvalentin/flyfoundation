<?php
namespace Flyf\Util;

use \Flyf\Core\Config as Config;
use \Flyf\Core\Request as Request;

use \Flyf\Util\Debug as Debug;

use \Flyf\Language\LanguageSettings as LanguageSettings;

use \Flyf\Models\Url\Rewrite as Rewrite;
use \Flyf\Models\Url\Redirect as Redirect;

class UrlHelper {
	private static $seo = array(
		'blok18/blog' => 'blog',
		'blog' => 'blog'
	);
	
	public static function GetUrl($url, $language = 'current', $absolute = false, $secure = false) {
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
				
					if (count($split = explode('=', $param)) > 1) {
						$key = $split[0];
						$value = $split[1];

						$parameters[$component[0]][$key] = $value; 
					}
				}
			}
		}
		
		if (!$absolute) {
			$currentComponents = $request->GetComponents();
			count($currentComponents) > 1 ? array_pop($currentComponents) : null;
			
			$components = array_merge($currentComponents, $components);
		}

		$targetComponent = $component[0];
		$targetParameters = isset($parameters[$targetComponent]) ? $parameters[$targetComponent] : array();
		
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

		$rewriteSeo = self::SubstituteSeo($rewriteSystem);
		
		$rewriteSeo .= '/'.self::FormatSeoParameters($targetComponent, $targetParameters);
		$rewriteSystem .= self::FormatSystemParameters($targetComponent, $targetParameters);

		$rewriteSeo = $request->GetDomain().$request->GetTLD().'/'.Config::GetValue('root_path').'/'.$rewriteSeo;
		$rewriteSystem = $request->GetDomain().$request->GetTLD().'/'.Config::GetValue('root_path').'/'.$rewriteSystem;

		$rewriteSeo = $secure ? 'https://'.$rewriteSeo : 'http://'.$rewriteSeo;
		$rewriteSystem = $secure ? 'https://'.$rewriteSystem : 'http://'.$rewriteSystem;

		self::StoreRewrites($rewriteSeo, $rewriteSystem);
		
		return $rewriteSeo;
	}

	private static function SubstituteSeo($key) {
		if (isset(self::$seo[$key])) {
			return self::$seo[$key];
		} else {
			Debug::Hint('The key "'.$key.'" does not exists in the seo-table in UrlHelper');

			return $key;
		}
	}

	private static function FormatSeoParameters($component, $parameters) {
		$controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $component)));
		$class = '\\Flyf\\Components\\'.$controller.'\\'.$controller."Controller";

		return $class::FormatSeoParameters($parameters);
	}

	private static function FormatSystemParameters($component, $parameters) {
		$controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $component)));
		$class = '\\Flyf\\Components\\'.$controller.'\\'.$controller."Controller";

		return $class::FormatSystemParameters($parameters);
	}

	private static function StoreRewrites($rewriteSeo, $rewriteSystem) {
		$rewrite = Rewrite::Load(array(
			'system' => $rewriteSystem
		));

		if ($rewrite->Exists()) {
			if ($rewrite->Get('seo') != $rewriteSeo) {
				$redirect = Redirect::Create(array(
					'from' => $rewrite->Get('seo'),
					'to' => $rewriteSeo
				));
				$redirect->Save();
				
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
}
?>
