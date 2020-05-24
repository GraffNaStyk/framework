<?php

namespace App\Facades\Validator;

use DateTime;

class Rules
{
    public static function min($item, $rule, $field)
    {
        if (strlen($item) < $rule) return 'Pole ' . $field . ' jest za krótkie';
    }

    public static function max($item, $rule, $field)
    {
        if (strlen($item) > $rule) return 'Pole ' . $field . ' jest za długie';
    }

    public static function required($item, $rule, $field)
    {
        return !isset($item) || empty($item) ? 'Pole ' . $field . ' jest wymagane' : '';
    }

    public static function email($item, $rule, $field)
    {
        if (!filter_var($item, FILTER_VALIDATE_EMAIL))
            return 'Pole ' . $field . ' musi być typu email';
    }

    public static function string($item, $rule, $field)
    {
        if (is_numeric($item))
            return 'Pole ' . $field . ' musi składać się tylko z liter';
    }

    public static function int($item, $rule, $field)
    {
        if (!is_numeric($item))
            return 'Pole ' . $field . ' musi składać się tylko z liczb';
    }

    public static function moreThanZero($item, $rule, $field)
    {
        if ($item < 0 || $item == 0)
            return 'Pole ' . $field . ' musi być większe niż zero';
    }

    public static function date($item, $rule, $field)
    {
        if (DateTime::createFromFormat($rule, $item) == false) {
            return 'Pole ' . $field . ' musi być datą';
        }
    }

    public static function json($item, $rule, $field)
    {
        json_decode($item);
        if (json_last_error() != 0)
            return 'Pole ' . $field . ' musi być jsonem';
    }

    public static function match($item, $rule, $field)
    {
        if(preg_match("$rule", $item))
            return 'Pole ' . $field . ' jest niepoprawne';
    }
}
