<?php


namespace FlyFoundation\Util;

use FlyFoundation\Dependencies\AppContext;

class SeoTools {
    use AppContext;

    public function forceLowerCaseUri(){
        $context = $this->getAppContext();
        $lowerCaseUri = strtolower($context->getUri());
        if($lowerCaseUri != $context->getUri()){
            Redirecter::redirect(
                $context->getBaseUrl()."/".$lowerCaseUri,
                RedirectType::MovedPermanently,
                $context->getParameters()
            );
        }
    }
} 