<?php

namespace App\Facades\Rules;

class RuleValidator
{
    private static string $ns = 'App\\Facades\\Rules\\';
    
    public static function getRules(string $rule): object
    {
        $object = self::$ns.ucfirst($rule).'Validator';
        return new $object;
    }
}
