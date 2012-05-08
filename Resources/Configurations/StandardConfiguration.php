<?php
namespace Flyf\Resources\Configurations;

use Flyf\Resources\Configurations\Configuration;
use Flyf\Core\Config;

class StandardConfiguration{
	public static function Apply(){
		Config::Set(array(
			'root_path' => 'root-path-is-not-set',
			"debug"=>false,
			'default_language' => 'en',
			'default_component' => 'SigniflyComingSoon',
			'notfound_component' => 'Standard404',
			'error_component' => 'StandardError',
			
			'database_hostname' => 'localhost',
			'database_username' => 'root',
			'database_password' => 'abcd1234',
			'database_database' => 'database-not-set',
			'database_charset' => 'utf8',
			'database_type' => 'mysql',
			'database_table_prefix' => 'flyf_',
			'charset' => 'utf-8',
			
			'debug_console_level' => array('error', 'log', 'hint'),
			'debug_file_level' => array('error', 'log', 'hint'),
			'debug_file_write' => array('multiple', 'single'),
			'debug_file_path' => 'Var/',
			
			'javascript_jquery' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js',
			'javascript_jquery_ui' => 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js'
		));
	}
}

?>