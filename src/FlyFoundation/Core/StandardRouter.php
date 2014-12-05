<?php


namespace FlyFoundation\Core;

use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\AppContext;
use FlyFoundation\Factory;

class StandardRouter implements Router{

    use AppContext, AppConfig;

    /**
     * @return SystemQuery
     */
    public function getSystemQuery()
    {
        $prefixedQuery = $this->getAppContext()->getHttpVerb().":".$this->getAppContext()->getUri();
        list($controller, $method, $arguments) = $this->parseQuery($prefixedQuery);
        $arguments = array_merge($this->getAppContext()->getParameters(),$arguments);

        /** @var SystemQuery $result */
        $result = Factory::load("\\FlyFoundation\\Core\\SystemQuery");
        $result->setController($controller);
        $result->setMethod($method);
        $result->setArguments($arguments);

        return $result;
    }

    public function parseQuery($query)
    {
        $routing = $this->getAppConfig()->routing->asArray();

        foreach($routing as $route)
        {
            $uriPattern = $route["uri"];
            $action = $route["action"];
            list($match, $arguments) = $this->matchUri($uriPattern, $query);

            if($match){

                list($controllerName, $method) = explode("#",$action);

                $controller = Factory::loadController($controllerName);
                $arguments = array_merge($arguments, $route["arguments"]);

                if($controller->respondsTo($method, $arguments)){
                    return [$controller, $method, $arguments];
                }

            }

        }

        $controller = Factory::loadController("Page");
        return [$controller, "pageNotFound", []];
    }

    public function matchUri($uriPattern, $query)
    {
        $uriEscaped = str_replace("/","\\/",$uriPattern);
        $uriRegexp = "/^".preg_replace('/\\{([a-zA-Z0-9\\-\\_]+)\\}/','(?<$1>.+)', $uriEscaped)."$/";
        $arguments = [];
        $matchesUri = preg_match($uriRegexp,$query,$arguments);
        if(!$matchesUri){
            return [false, []];
        }
        return [true, $arguments];
    }

}