<?php
namespace Flyf\Resources\Configurations;

use Flyf\Resources\Configurations\Configuration;
use Flyf\Core\Config;

class StandardConfiguration{
	public static function Apply(){
		Config::Set(array(
			'root_path' => 'root-path-is-not-set',
			"debug"=>false,
			'default_language' => 'da',
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
			'debug_file_path' => 'Var/'
		));
	}
}

?>