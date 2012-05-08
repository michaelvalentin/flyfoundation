<?php
namespace Flyf\Resources;

class Javascript {
	public static function AddJquery(){
		$response = \Flyf\Core\Response::GetResponse();
		$response->AddExternalJs(\Flyf\Core\Config::GetValue("javascript_jquery"));	
	}
	
	public static function AddJqueryUi(){
		$response = \Flyf\Core\Response::GetResponse();
		$response->AddExternalJs(\Flyf\Core\Config::GetValue("javascript_jquery_ui"));
	}
}

?>