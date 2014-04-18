<?php


namespace FlyFoundation\Core;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Controllers\PageController;

class StandardRouter implements Router{

    use Environment;

    /**
     * @param $query
     * @return SystemQuery
     */
    public function getSystemQuery($query)
    {
        $prefixedQuery = $this->getContext()->getHttpVerb().":".$query;
        list($controller, $method, $arguments) = $this->parseQuery($prefixedQuery);
        $arguments = array_merge($this->getContext()->getParameters(),$arguments);

        $result = new SystemQuery();
        $result->setController($controller);
        $result->setMethod($method);
        $result->setArguments($arguments);

        return $result;
    }

    public function parseQuery($query)
    {
        $routings = $this->getConfig()->routing->asArray();

        foreach($routings as $routing)
        {
            $uriPattern = $routing["uri"];
            $action = $routing["action"];
            list($match, $arguments) = $this->matchUri($uriPattern, $query);

            if($match){

                list($controllerName, $method) = explode("#",$action);

                $controller = $this->getFactory()->loadController($controllerName);
                $arguments = array_merge($arguments, $routing["arguments"]);

                if($controller->respondsTo($method, $arguments)){
                    return [$controller, $method, $arguments];
                }

            }

        }

        $controller = $this->getFactory()->loadController("Page");
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