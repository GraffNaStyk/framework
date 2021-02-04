<?php

namespace App\Facades\Validator;

use DateTime;

class Rules
{
    public static function min($item, $rule, $field): ?array
    {
        if (is_numeric($item)) {
            if ($item < $rule) {
                return ['msg' => 'Pole jest za krótkie', 'field'=> $field];
            }
        } else {
            if (strlen($item) < $rule) {
                return ['msg' => 'Pole jest za krótkie', 'field'=> $field];
            }
        }
    }
    
    public static function max($item, $rule, $field): ?array
    {
        if (is_numeric($item)) {
            if ($item > $rule) {
                return ['msg' => 'Pole jest za długie', 'field'=> $field];
            }
        } else {
            if (strlen($item) > $rule) {
                return ['msg' => 'Pole jest za długie', 'field'=> $field];
            }
        }
    }

    public static function required($item, $rule, $field): ?array
    {
        if (! isset($item) || empty($item)) {
            return ['msg' => 'Pole jest wymagane', 'field'=> $field];
        }
    }

    public static function email($item, $rule, $field): ?array
    {
        if (! filter_var($item, FILTER_VALIDATE_EMAIL)) {
            return ['msg' => 'Pole musi być typu email', 'field'=> $field];
        }
    }

    public static function string($item, $rule, $field): ?array
    {
        if (is_numeric($item)) {
            return ['msg' => 'Pole musi składać się tylko z liter', 'field'=> $field];
        }
    }

    public static function int($item, $rule, $field): ?array
    {
        if (!is_numeric($item)) {
            return ['msg' => 'Pole musi składać się tylko z liczb', 'field'=> $field];
        }
    }

    public static function moreThanZero($item, $rule, $field): ?array
    {
        if ((int) $item <= 0) {
            return ['msg' => 'Pole musi być większe niż zero', 'field'=> $field];
        }
    }

    public static function date($item, $rule, $field): ?array
    {
        if ((bool) DateTime::createFromFormat($rule, $item) === false) {
            return ['msg' => 'Zły format daty', 'field'=> $field];
        }
    }

    public static function json($item, $rule, $field): ?array
    {
        json_decode($item);
        
        if (json_last_error() !== 0) {
            return ['msg' => 'Pole musi być jsonem', 'field'=> $field];
        }
    }

    public static function match($item, $rule, $field): ?array
    {
        preg_match("$rule", $item, $m);

        if (empty($m)) {
            return ['msg' => 'Pole jest niepoprawne', 'field'=> $field];
        }
    }

    public static function unique($item, $rule, $field): ?array
    {
        if ($rule::select([$field])->where($field, '=', $item)->exist()) {
            return ['msg' => 'Taka nazwa już istnieje', 'field'=> $field];
        }
    }
    
    public static function checkFile($item, $rule, $field): ?array
    {
        if ($item['error'] !== UPLOAD_ERR_OK) {
            return ['msg' => 'Plik jest uszkodzony', 'field'=> $field];
        }
    }
}
