<?php

namespace Flyf\Modules;

/**
 * TODO: Write class description
 * 
 * @Package: Flyf\Modules
 * @Author: Michael Valentin
 * @Created: 07-08-13 - 17:51
 */
class SystemController {
    public function PageNotFound(){
        header("HTTP/1.0 404 Not Found");
        echo "404 - Page not found :-(";
    }
}