<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Environment;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppContext;
use FlyFoundation\Factory;
use FlyFoundation\Util\SeoTools;

class StandardBaseController implements BaseController{

    use AppContext;
    use AppConfig;

    /**
     * @param Response $response
     * @return Response
     */
    public function beforeApp(Response $response)
    {
        // TODO: Implement beforeApp() method.
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function beforeController(Response $response)
    {
        /** @var SeoTools $seoTools */
        $seoTools = Factory::loadWithoutImplementationSearch("\\FlyFoundation\\Util\\SeoTools");
        $seoTools->forceLowerCaseUri();
        $response->setDataValue("baseurl",$this->getAppContext()->getBaseUrl());

        /* Globals */
        $globals_path = $this->getAppConfig()->get('globals_path', '/globals.json');
        if(file_exists($globals_path)){
            $response->setData(json_decode(file_get_contents($globals_path), true));
        }

        return $response;
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function afterController(Response $response)
    {
        //TODO: This is silly, lets change it soon ;-)
        $response->wrapInTemplateFile('base');
        return $response;
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function afterApp(Response $response)
    {
        // TODO: Implement afterApp() method.
    }
}