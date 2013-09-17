<?php
/**
 * 
 *
 * @Author: MV
 * @Created: 09-08-13 - 01:19
 */

namespace Flyf\_tests;

use Flyf\Util\Request;

require_once 'init.php';

class RequestTest extends \PHPUnit_Framework_TestCase {
    public function testDemo(){
        $request = Request::GetRequest()->AsArray();
    }
}
