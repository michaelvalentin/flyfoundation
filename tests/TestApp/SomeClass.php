<?php


namespace TestApp;

use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppContext;
use FlyFoundation\Dependencies\AppDefinition;

class SomeClass {
    use AppContext, AppConfig, AppDefinition;
} 