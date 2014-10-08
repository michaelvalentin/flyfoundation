<?php


namespace FlyFoundation\Controllers;


use FlyFoundation\Core\Environment;
use FlyFoundation\Core\Response;
use FlyFoundation\Dependencies\AppContext;
use FlyFoundation\Factory;
use FlyFoundation\Util\SeoTools;

class StandardBaseController implements BaseController{

    use AppContext;

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
        $seoTools = Factory::load("\\FlyFoundation\\Util\\SeoTools");
        $seoTools->forceLowerCaseUri();
        $response->setDataValue("baseurl",$this->getAppContext()->getBaseUrl());
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