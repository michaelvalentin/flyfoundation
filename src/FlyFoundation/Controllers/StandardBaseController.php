<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Environment;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppContext;
use FlyFoundation\Dependencies\AppResponse;
use FlyFoundation\Factory;
use FlyFoundation\Util\SeoTools;

class StandardBaseController implements BaseController{

    use AppContext;
    use AppConfig;
    use AppResponse;

    public function beforeApp()
    {
        // TODO: Implement beforeApp() method.
    }

    public function beforeController()
    {
        /** @var SeoTools $seoTools */
        $seoTools = Factory::loadWithoutImplementationSearch("\\FlyFoundation\\Util\\SeoTools");
        $seoTools->forceLowerCaseUri();
        $this->getAppResponse()->setDataValue("baseurl",$this->getAppContext()->getBaseUrl());

        /* Globals */
        $globals_path = $this->getAppConfig()->get('globals_path', '/globals.json');
        if(file_exists($globals_path)){
            $this->getAppResponse()->setData(json_decode(file_get_contents($globals_path), true));
        }
    }

    public function afterController()
    {
        //TODO: This is silly, lets change it soon ;-)
        $this->getAppResponse()->wrapInTemplateFile('base');
    }

    public function afterApp()
    {
        // TODO: Implement afterApp() method.
    }
}