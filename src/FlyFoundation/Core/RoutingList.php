<?php


namespace FlyFoundation\Core;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Util\Map;
use FlyFoundation\Util\ValueList;

class RoutingList extends ValueList{
    public function addRouting($uri, $action, $arguments = [])
    {
        if(!$this->validateUri($uri)){
            throw new InvalidArgumentException(
                "The uri: '".$uri."' is not a valid uri for routing. Uri's for are like: GET:my-stuff/{argument}"
            );
        }
        if(!$this->validateSystemQuery($action)){
            throw new InvalidArgumentException(
                "The action: '".$action."' is not a valid action for routing. Actions are like Blogpost#allByAuthor"
            );
        }

        parent::add(["uri"=>$uri,"action"=>$action, "arguments" => $arguments]);
    }

    public function add($value)
    {
        $values = explode("=>",$value);
        if(count($values) != 2){
            throw new InvalidArgumentException(
                "Routing rules must be of the format: url/to-my-stuff/{alias} => ModelName#action"
            );
        }
        $this->addRouting(trim($values[0]),trim($values[1]));
    }

    private function validateUri($value)
    {
        $pattern = "/^(GET|POST|PUT|DELETE):(([a-z0-9\\/\\-\\_]+)|(\\{[a-z0-9\\_]+\\}))*(\\.[a-z0-9]+)?$/";
        return preg_match($pattern, $value);
    }

    private function validateSystemQuery($value)
    {
        $pattern = "/^([A-Z][A-Za-z\\\\]+)#([a-z][A-Za-z]+)$/";
        return preg_match($pattern, $value);
    }
} 