<?php
use \Core\Config;

/**
 * Configuration used by the system, which are not project-specific
 */

$debugwords = array(
    "Show response data" => "responsedata",
    "Profile program" => "profile",
    "Show server request data" => "serverdata",
    "Close debug" => "0"
);

Config::Set(array(
    "debugwords" => $debugwords
));