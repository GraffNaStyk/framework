<?php

namespace App\Rules;

class RuleValidator
{
    private static string $ns = 'App\\Rules\\';
    
    public static function getRules(string $rule): object
    {
        $object = self::$ns.ucfirst($rule).'Validator';
        return new $object;
    }
}
