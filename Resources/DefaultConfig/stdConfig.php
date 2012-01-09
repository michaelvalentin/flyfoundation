<?php
Flyf\Core\Config::Set(array(
	'root_path' => '',
	"debug"=>true,
	'default_language' => 'da',

	'database_hostname' => 'localhost',
	'database_username' => 'signifly',
	'database_password' => 'abcd1234',
	'database_database' => '',
	'database_charset' => 'utf8',

	'meta_type' => 'text/html',
	'meta_charset' => 'utf-8',

	'debug_console_level' => array('error', 'log', 'hint'),
	'debug_file_level' => array('error', 'log', 'hint'),
	'debug_file_write' => array('multiple', 'single'),
	'debug_file_path' => 'Var/'/*,

	'profiler_console_output' => true,
	'profiler_file_output' => true,
	'profiler_file_write' => array('multiple', 'single'),
	'profiler_file_path' => 'Var/'*/
));