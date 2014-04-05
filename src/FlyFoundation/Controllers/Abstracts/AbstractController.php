<?php
namespace Controllers\Abstracts;


use Core\Config;
use Core\Request;

/**
 * Class AbstractController
 *
 * Abstract controller, handling templates and routing request based on http-method automatically.
 * To us, extend and implement the RenderGet method as a minimum.
 *
 * @package Controllers\Abstracts
 */
abstract class AbstractController implements IController{
    protected $args;
    protected $template;
    protected $base_template;

    public function __construct($args = array()){
        $this->args = $args;
        $this->base_template = Config::Get("DefaultBaseTemplate");
        preg_match("/Controllers\\\\(.+)Controller/",get_class($this),$matches);
        $this->template = $matches[1];
    }

    public function Render(\Core\Response $response){
        $http_method = Request::getRequest()->getHttpMethod();
        $method_name = "Render".ucfirst(strtolower($http_method));
        $response = $this->$method_name($response);

        //Outermost templates, easily added by default..
        if($this->template) $response->WrapInTemplate($this->template);
        if($this->base_template) $response->WrapInTemplate($this->base_template);

        return $response;
    }

    public abstract function RenderGet(\Core\Response $response);

    public function RenderPost(\Core\Response $response){
        return $this->RenderGet($response);
    }
} 