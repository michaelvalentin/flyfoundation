<?php
namespace Flyf\Examples;

error_reporting(E_ALL);
ini_set('display_errors', true);

$profiles = array(
	
);

$pointer = &$profiles;

$profiles['total'] = array(
	'key' => 'total',
	'start' => microtime(),
	'parent' => null,
	'children' => array()
);

$pointer = &$profiles['total'];

print_r($profiles);

$parent = $pointer;

$pointer['children']['second'] = array(
	'key' => 'second',
	'start' => microtime(),
	'parent' => $parent,
	'children' => array()
);

$pointer = &$pointer['children']['second'];

print_r($profiles);

$pointer = &$pointer['parent'];


echo 'her';
print_r($pointer);


/*
$pointer['children']['third'] = array(
	'start' => microtime(),
	'parent' => &$pointer,
	'children' => array()
);

$pointer = &$pointer['children']['third'];

print_r($pointer);
print_r($profiles);

/*
// first
$profiles['first'] = array(
	'start' => microtime()
);


$pointer = &$profiles['first'];

// second
$pointer['children']['second'] = array(
	'start' => microtime();
);
/*
$pointer = &$profiles['second'];
*/

#print_r($profiles);
?>
