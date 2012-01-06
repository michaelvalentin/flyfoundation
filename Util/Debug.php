<?php
namespace Flyf\Util;

use \Flyf\Core\Config as Config;

/**
 * Static debug class. The debug class consist of three
 * debug methods; Error, Log and Hint. It is not explicitly
 * declared when to use what level/method, this decision
 * is left to the end-user to evaluate.
 *
 * One need to set the following parameters in the config.php
 * file for the class to work properbly:
 * 
 * debug_console_level (which levels to output to the screen, as an array(error, hint, log))
 * debug_file_level (which levels to output to a file, as an array(error, hint, log))
 * debug_file_write (whether to output one single file, multiple files or both, as an array(multiple, single))
 * debug_file_path (the file path where the files should be outputtet)
 * 
 * If one wishes to output the debugging to a file, then
 * it is required to call the Flush method at the end of
 * the application (or when there is no more debugging to do).
 * This is a I/O optimization, so the debugger does not have
 * to write to the local file system more than once per run.
 *
 * @example
 * // Config::GetValue('debug_console_level') == array('error', 'hint', 'log')
 * 
 * // Config::GetValue('debug_file_level') == array('error', 'hint', 'log')
 * // Config::GetValue('debug_file_write') == array('multiple')
 * // Config::GetValue('debug_file_path') == array('var/')
 * 
 * Debug::Error("Something when totally wrong here");
 * Debug::Log("Loaded some class, went pretty well");
 * Debug::Hint("Yo, something is not quite right. Check your paths");
 *
 * Debug::Flush();
 *
 * @author Henrik Haugbølle <hh@signifly.com>
 * @version 2012-01-06
 * 
 * @dependencies Config
 */
class Debug {
	// Which levels of debugging to output to the screen
	private static $consoleLevel;

	// Which levels of debugging to output to a file
	private static $fileLevel;
	// Whether to write a single, multiple of both types of files
	private static $fileWrite;
	// Where the file(s) should be located
	private static $filePath;

	// Used to check of internal initialization because of the static-ness of the class
	private static $init = false;
	// Used to buffer the debugging to file(s)
	private static $buffer = '';

	/**
	 * Initialization of the static variables fetched from the 
	 * Config class. Will only initialize once.
	 */
	public static function Init() {
		if (!self::$init) {
			self::$consoleLevel = Config::GetValue('debug_console_level');
		
			self::$fileLevel = Config::GetValue('debug_file_level');
			self::$fileWrite = Config::GetValue('debug_file_write');
			self::$filePath = Config::GetValue('debug_file_path');	
		}
	}

	/**
	 * Debug at error level. The guideline is to use
	 * this method when there is an application fault.
	 * 
	 * @param string $output
	 */
	public static function Error($output) {
		self::Init();
		
		if (in_array('error', self::$consoleLevel)) {
			self::Console('Error: '.$output);
		}

		if (in_array('error', self::$fileLevel)) {
			self::Buffer('Error: '.$output);
		}
	}

	/**
	 * Debug at log level. The guideline is to use
	 * this method when one wants to do some trivial
	 * logging, which is not nessecary an error.
	 * 
	 * @param string $output
	 */
	public static function Log($output) {
		self::Init();
		
		if (in_array('log', self::$consoleLevel)) {
			self::Console('Log: '.$output);
		}

		if (in_array('log', self::$fileLevel)) {
			self::Buffer('Log: '.$output);
		}
	}

	/**
	 * Debug at hint level. The guideline is to use
	 * this method when one wants to give a developer
	 * a hint, eventually when he/she is doing something
	 * wrong.
	 * 
	 * @param string $output
	 */
	public static function Hint($output) {
		self::Init();
		
		if (in_array('hint', self::$consoleLevel)) {
			self::Console('Hint: '.$output);
		}

		if (in_array('hint', self::$fileLevel)) {
			self::Buffer('Hint: '.$output);
		}
	}

	/**
	 * Private method for uniformly formatting
	 * of all input before outputting to screen.
	 * 
	 * @param string $output
	 */
	private static function Console($output) {
		echo $output."<br />\r\n";
	}
	
	/**
	 * Private method for uniformly formatting
	 * of all input before written to file buffer.
	 *
	 * Add's a timestamp to the output.
	 * 
	 * @param string $output
	 */
	private static function Buffer($output) {
		self::$buffer .= date('Y-m-d H:i:s')."\r\n".$output."\r\n\r\n";
	}
	
	/**
	 * Needs to be called when a developer wants
	 * to output his/hers debugging to a file(s).
	 *
	 * The intended purpose of this method is to
	 * be called only once during an application run.
	 * It is recommanded to call the method very close
	 * to the termination of the application run, to 
	 * ensure that all debugging will be written to the
	 * file(s).
	 */
	public static function Flush() {
		self::Init();
		
		if (in_array('multiple', self::$fileWrite)) {
			file_put_contents(self::$filePath.'log-'.date('Y-m-d H-i-s').'.txt', self::$buffer);
		}

		if (in_array('single', self::$fileWrite)) {
			self::$buffer = "===\r\n\r\n".self::$buffer;
			
			file_put_contents(self::$filePath.'log.txt', self::$buffer, FILE_APPEND);
		}

		self::$buffer = '';
	}
}
?>
