<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Environment;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppContext;
use FlyFoundation\Factory;
use FlyFoundation\Util\SeoTools;

class StandardBaseController implements BaseController{

    use AppContext;

    public function beforeController(Response $response)
    {
        /** @var SeoTools $seoTools */
        $seoTools = Factory::load("\\FlyFoundation\\Util\\SeoTools");
        $seoTools->forceLowerCaseUri();
        $response->setDataValue("baseurl",$this->getAppContext()->getBaseUrl());
        return $response;
    }

    public function afterController(Response $response)
    {
        //TODO: This is silly, lets change it soon ;-)
        $response->wrapInTemplate('<div style="width:50%; margin: 30px auto;">{{{content}}}</div>');
        return $response;
    }
}