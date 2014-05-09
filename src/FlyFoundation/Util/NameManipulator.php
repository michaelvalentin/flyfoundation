<?php


namespace FlyFoundation\Util;


use FlyFoundation\Exceptions\InvalidArgumentException;

class NameManipulator {

    /**
     * @param string $name
     * @return string
     */
    public function toCamelUpperCaseFirst($name)
    {
        if($this->isCamelLowerCaseFirst($name)){
            return ucfirst($name);
        }

        if($this->isCamelUpperCaseFirst($name)){
            return $name;
        }

        if($this->isUnderscored($name)){
            $name = preg_replace_callback("/_([a-zA-Z0-9])/",function($matches){
                return strtoupper($matches[1]);
            },$name);
            return ucfirst($name);
        }

        throw new InvalidArgumentException("The name '".$name."' could not be recognized as a valid name. Valid names contains only letters a-z, A-Z, 0-9 and underscores, and has no spaces. They should be formated in camelCase, PascalCase or lowercase underscored.");
    }

    /**
     * @param string $name
     * @return string
     */
    public function toCamelLowerCaseFirst($name)
    {
        if($this->isCamelLowerCaseFirst($name)){
            return $name;
        }

        if($this->isCamelUpperCaseFirst($name)){
            return lcfirst($name);
        }

        if($this->isUnderscored($name)){
            $name = strtolower($name);
            return preg_replace_callback("/_([a-zA-Z0-9])/",function($matches){
                return strtoupper($matches[1]);
            },$name);
        }

        throw new InvalidArgumentException("The name '".$name."' could not be recognized as a valid name. Valid names contains only letters a-z, A-Z, 0-9 and underscores, and has no spaces. They should be formated in camelCase, PascalCase or lowercase underscored.");
    }

    /**
     * @param string $name
     * @return string
     */
    public function toUnderscored($name)
    {
        if($this->isCamelLowerCaseFirst($name)){
            return preg_replace_callback("/([A-Z])/",function($matches){
                return "_".strtolower($matches[1]);
            },$name);
        }

        if($this->isCamelUpperCaseFirst($name)){
            $name = preg_replace_callback("/([A-Z])/",function($matches){
                return "_".strtolower($matches[1]);
            },$name);
            return ltrim($name,"_");
        }

        if($this->isUnderscored($name)){
            return strtolower($name);
        }

        throw new InvalidArgumentException("The name '".$name."' could not be recognized as a valid name. Valid names contains only letters a-z, A-Z, 0-9 and underscores, and has no spaces. They should be formated in camelCase, PascalCase or lowercase underscored.");
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isCamelUpperCaseFirst($name)
    {
        return preg_match("/^[A-Z][a-zA-Z0-9]*$/",$name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isCamelLowerCaseFirst($name)
    {
        return preg_match("/^[a-z0-9][a-zA-Z0-9]*$/",$name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isUnderscored($name)
    {
        return preg_match("/^([a-z0-9]+_)*[a-z0-9]+$/",$name);
    }
} 