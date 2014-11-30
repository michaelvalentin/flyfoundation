<?php


namespace FlyFoundation\LsdParser;


use FlyFoundation\Util\Enum;

abstract class DirectiveType extends Enum{

    //Types that are commented out, are not yet implemented in the system

    const System = 1;
    const Entity = 2;
    //const AbstractEntity = 3;
    //const Inclusion = 4;
    const EntityField = 5;
    //const CalculatedEntityField = 6;
    //const HasARelation = 7;
    //const HasManyRelation = 8;
    //const ManyToManyRelation = 9;
    const Validation = 10;
    //const Filter = 11;
    //const Index = 12;
    //const AccessControl = 13;
    //const NewAccessControl = 14;
    const Setting = 15;

    public static function FromSymbol($typeSymbol)
    {
        if(!$typeSymbol){
            return self::Setting;
        }

        switch($typeSymbol)
        {
            case "!" :
                return self::Entity;
                break;
            case "?" :
                return self::AbstractEntity;
                break;
            case "&" :
                return self::Inclusion;
                break;
            case "$" :
                return self::EntityField;
                break;
            case "*" :
                return self::CalculatedEntityField;
                break;
            case "<" :
                return self::HasARelation;
                break;
            case ">" :
                return self::HasManyRelation;
                break;
            case "><" :
                return self::ManyToManyRelation;
                break;
            case "#" :
                return self::Validation;
                break;
            case "~" :
                return self::Filter;
                break;
            case "%" :
                return self::Index;
                break;
            case "@" :
                return self::AccessControl;
                break;
            case "+@" :
                return self::NewAccessControl;
                break;
            default :
                return null;
        }
    }
} 