<?php

namespace Controllers\Specials;

use Controllers\Abstracts\AbstractController;
use Core\Request;

/**
 * Class BaseController
 *
 * BaseController sets up standard variables and adds standard data to the response
 *
 * @package Controllers\Specials
 */
class BaseController extends AbstractController{

    public function RenderGet(\Core\Response $response)
    {
        $baseurl = Request::getRequest()->getBaseUrl();
        $response->AddData(array(
            "baseurl" => $baseurl
        ));
        $this->template = false;
        $this->base_template = false;

        $response->Title = "Default title";
        $response->MetaData->SetDescription("Default page description");
        $response->AddJs($baseurl."/js/global.js");
        $response->AddJs("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");
        $response->AddCss($baseurl."/css/screen.css");
        $response->MetaData->Set("X-UA-Compatible","IE=edge");
        $response->MetaData->Set("viewport","width=device-width, initial-scale=1.0");

        return $response;
    }
}