<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Util\Enum;

class FieldDefinition extends DefinitionComponent
{
    private $fieldType;
    private $entityDefinition;

    public function setType($fieldType)
    {
        $this->requireOpen();
        if(!FieldType::isValidValue($fieldType)){
            throw new InvalidArgumentException(
                "The field type supplied is not valid. Use eg: FieldType::Integer"
            );
        }
        $this->fieldType = $fieldType;
    }

    public function getType()
    {
        return $this->fieldType;
    }

    public function setEntityDefinition(EntityDefinition $entityDefinition)
    {
        $this->requireOpen();
        $this->entityDefinition = $entityDefinition;
    }

    public function getEntityDefinition()
    {
        return $this->entityDefinition;
    }
}

abstract class FieldType extends Enum
{
    const Integer = 1;
    const Float = 2;
    const String = 3;
    const DateTime = 4;
    const Date = 5;
    const Time = 6;
    const Boolean = 7;
}