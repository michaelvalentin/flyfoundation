<?php

use \Core\Config;

/**
 * Configuration of the system. Note that this should ONLY be used for system-wide configurations,
 * that are not managed by the user. This is developer stuff and defaults.
 */

Config::Set(array(
   "BaseController" => new \Controllers\Specials\BaseController(),
   "Parser" => new \Core\Parser(),
   "DefaultBaseTemplate" => "Base",
   "demo_domains" => array(
       "localhost",
       "basedemo.signifly.com"
   ),
   "production_domain" => "base.signifly.com"
));