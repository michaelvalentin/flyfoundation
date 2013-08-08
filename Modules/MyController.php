<?php

namespace Flyf\Modules;
use Flyf\Util\Request;

/**
 * TODO: Write class description
 * 
 * @Package: Flyf\Modules
 * @Author: Michael Valentin
 * @Created: 07-08-13 - 22:46
 */
class MyController implements iController{
    public function Show(){
        echo ": : : : : : : :  HELLO WORLD! HERE IS YOUR REQUEST! : : : : : : : : ";
        echo "<pre>";
        print_r(Request::GetRequest()->AsArray());
        echo "</pre>";
    }
}