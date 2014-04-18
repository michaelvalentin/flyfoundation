<?php


namespace FlyFoundation\Controllers;


class StandardBaseController extends AbstractBaseController{

    public function render()
    {
        $response = $this->getBaseResponse();
        $response->setDataValue("baseurl",$this->getContext()->getBaseUrl());
        return $response;
    }
}