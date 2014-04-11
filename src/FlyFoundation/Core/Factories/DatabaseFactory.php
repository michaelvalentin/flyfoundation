<?php


namespace FlyFoundation\Core\Factories;


class DatabaseFactory extends AbstractFactory{

    /**
     * @param string $className
     * @param array $arguments
     * @return object
     */
    public function load($className, $arguments = array())
    {
        $dataObjectNaming = "/^(.*)\\\\(.*)(DataMapper|DataFinder|DataMethods)$/";
        $matches = [];
        $hasDataObjectNaming = preg_match($dataObjectNaming, $className, $matches);

        if(!$hasDataObjectNaming){
            return $this->getFactory()->loadWithoutOverridesAndDecoration($className,$arguments);
        }

        $base = $matches[1];
        $modelName = $matches[2];
        $type = $matches[3];

        if(!class_exists($className) && !interface_exists($className)){
            $modelName = "Dynamic";
        }

        if(!class_exists($className) && $hasDataObjectNaming){

        }else{
            //If it's not a DataObject just load it normally
            $object = $this->getFactory()->loadWithoutOverridesAndDecoration($className,$arguments);
        }




        //Prefix with database vendor
        if($hasDataObjectNaming){


            $modelNameWithPrefix = $this->getConfig()->get("database_type_class_prefix").$modelName;

            $className = $base.$modelNameWithPrefix.$type;
        }

        //Find the related Entity

        //Instantiate with this entity and return
    }
}