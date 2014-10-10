<?php


namespace FlyFoundation\Database\Conditions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class ContainsStringCondition extends DataCondition{

    private $searchString;

    public function setSearchString($searchString)
    {
        if(!is_string($searchString)){
            throw new InvalidArgumentException(
                "Search string must be of type string"
            );
        }

        $this->searchString = $searchString;
    }

    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @return bool
     */
    public function readyForUse()
    {
        if(!isset($this->getFieldNames()[0])){
            $this->error = "A contains string condition must work on exactly one field";
            return false;
        }
        if(!$this->getSearchString()){
            $this->error = "A contains string condition must have a search string to match";
            return false;
        }
        if(count($this->getFieldNames()) != 1){
            $this->error = "A contains string condition must work on exactly one field";
            return false;
        }
        return true;
    }
}