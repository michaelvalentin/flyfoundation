<?php


namespace FlyFoundation\Util;


use FlyFoundation\Core\Environment;

class SeoTools {
    use Environment;

    public function forceLowerCaseUri(){
        $context = $this->getContext();
        $lowerCaseUri = strtolower($context->getUri());
        if($lowerCaseUri != $context->getUri()){
            Redirecter::Redirect(
                $context->getProtocol()."://".$context->getBaseUrl().$lowerCaseUri,
                RedirectType::MovedPermanently,
                $context->getParameters()
            );
        }
    }
} 