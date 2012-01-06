<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

require_once '../../Core/Dispatcher.php';
\Flyf\Core\Dispatcher::Init();

\Flyf\Core\Config::Setup(array(
	'profiler_console_output' => true,
	'profiler_file_output' => true,
	'profiler_file_write' => array('multiple', 'single'),
	'profiler_file_path' => 'Var/'
));

use \Flyf\Util\Profiler as Profiler;

Profiler::Start('root');
	Profiler::Start('first');

	Profiler::Stop('first');

Profiler::Stop('root');

Profiler::Output();

?>
