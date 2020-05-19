<?php

namespace App\Facades\Validator;

use DateTime;

class Rules
{
    public static function min($item, $rule, $field)
    {
        if (strlen($item) < $rule) return $field . ' is too short!';
    }

    public static function max($item, $rule, $field)
    {
        if (strlen($item) > $rule) return $field . ' is too long!';
    }

    public static function required($item, $rule, $field)
    {
        return !isset($item) || empty($item) ? 'Field ' . $field . ' is required!' : '';
    }

    public static function email($item, $rule, $field)
    {
        if (!filter_var($item, FILTER_VALIDATE_EMAIL))
            return 'Field ' . $field . ' must be a email!';
    }

    public static function string($item, $rule, $field)
    {
        if (is_numeric($item))
            return 'Field ' . $field . ' must be a string!';
    }

    public static function int($item, $rule, $field)
    {
        if (!is_numeric($item))
            return 'Field ' . $field . ' must be a integer type!';
    }

    public static function moreThanZero($item, $rule, $field)
    {
        if ($item < 0 || $item == 0)
            return 'Field ' . $field . ' must be a more then zero!';
    }

    public static function date($item, $rule, $field)
    {
        if (DateTime::createFromFormat($rule, $item) == false) {
            return 'Field ' . $field . ' must be a date!';
        }
    }

    public static function json($item, $rule, $field)
    {
        json_decode($item);
        if (json_last_error() != 0)
            return 'Field ' . $field . ' must be a json!';
    }

    public static function match($item, $rule, $field)
    {
        if(preg_match("$rule", $item))
            return 'Field ' . $field . ' is not valid';
    }
}
