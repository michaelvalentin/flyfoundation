<?php


namespace FlyFoundation\Controllers;


class StandardBaseController extends AbstractBaseController{

    public function beforeRender()
    {
        $response = $this->getBaseResponse();
        $response->setDataValue("baseurl",$this->getContext()->getBaseUrl());
        return $response;
    }

    public function afterRender()
    {
        //TODO: This is silly, lets change it soon ;-)
        $response = $this->getBaseResponse();
        $response->wrapInTemplate('<div style="width:50%; margin: 30px auto;">{{{content}}}</div>');
        return $response;
    }
}