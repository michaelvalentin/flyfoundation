<?php

namespace Flyf\Modules;
use Flyf\Util\HtmlString;
use Flyf\Util\Implementation;

/**
 * Controller interface
 *
 * @Author: MV
 * @Created: 07-08-13 - 17:49
 */
interface iController {
    /**
     * Render this controller and get the Html-output
     *
     * @param Request $request
     * @return HtmlString The compiled Html-output for the controller
     */
    //public function RenderHtml(Request $request);

    /**
     * Render this controller and get the Json-output
     *
     * @param Request $request
     * @return mixed
     */
    //public function RenderJson(Request $request);
}