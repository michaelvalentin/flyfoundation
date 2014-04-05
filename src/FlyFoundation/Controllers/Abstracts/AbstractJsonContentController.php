<?php

namespace Controllers\Abstracts;

use Core\Response;
use Models\JsonContentModel;

/**
 * Class AbstractJsonContentController
 *
 * Asbstract controller, automatically loading a JSON-model from content
 *
 * @package Controllers\Abstracts
 */
abstract class AbstractJsonContentController extends AbstractController{
    protected $models;

    public function __construct($args = array()){
        parent::__construct($args);
        $this->models = new JsonContentModel($this->template);
    }

    public final function Render(Response $response){
        $response = parent::Render($response);

        //The model is included in the response
        $response->SetData("models",$this->models->AsArray());

        return $response;
    }
}