<?php

namespace FlyFoundation;

use Controllers\Abstracts\IController;
use Exceptions\InvalidArgumentException;
use FlyFoundation\Core\Response;
use Util\Profiler;


class App {

    /**
     * @param string $query
     * @param Context $context
     */
    public function serve($query, Context $context = null){
        $this->getResponse($query, $context)->Output();
    }

    /**
     * @param string $query
     * @param Context $context
     * @return Response
     */
    public function getResponse($query, Context $context = null){
        //!TODO: Implement
        // Build configuration
        // Instansiate factory
        // Load router
        // Get controller
        // Get response
        // Return response
    }

    public function getDefaultContext(){

    }

    public function addConfigs($path){

    }
}
