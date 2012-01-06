<?php
namespace Flyf\Util;

use \Flyf\Core\Config as Config;

class Profiler {
	private static $consoleOutput;
	
	private static $fileOutput;
	private static $fileWrite;
	private static $filePath;

	private static $init = false;
	private static $profiles = array();

	public static function Init() {
		if (!self::$init) {
			self::$consoleOutput = Config::GetValue('profiler_console_output');
		
			self::$fileOutput = Config::GetValue('profiler_file_output');
			self::$fileWrite = Config::GetValue('profiler_file_write');
			self::$filePath = Config::GetValue('profiler_file_path');
		}
	}

	public static function Start($key) {
		
	}

	public static function Stop($key) {

		
	}

	public static function Output() {
		
	}

	public static function Flush() {

	}
}
?>
