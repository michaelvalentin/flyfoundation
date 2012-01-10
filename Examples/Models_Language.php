<?php
/*
 * A demo of \Flyf\Models\Core\Language
 */
namespace Flyf\Examples;

require_once 'init.php';

use \Flyf\Models\Core\Language;


$langRes = Language::Resource();

$langs = $langRes->Build();
print_r($langs);