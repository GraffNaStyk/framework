<?php

namespace App\Facades\Validator;

use App\Facades\Http\Router\Router;
use DateTime;

class Rules
{
    public static array $lang = [];

    public static function min($item, $rule, $field)
    {
        if ($item < $rule) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Zbyt mała wartość', 'field' => $field];
        }
    }

    public static function max($item, $rule, $field)
    {
        if ($item > $rule) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Zbyt duża wartość', 'field' => $field];
        }
    }

    public static function min_len($item, $rule, $field)
    {
        if (strlen($item) < $rule) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole jest za krótkie', 'field' => $field];
        }
    }

    public static function max_len($item, $rule, $field)
    {
        if (strlen($item) > $rule) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole jest za długie', 'field' => $field];
        }
    }

    public static function same_as($item, $rule, $field)
    {
        $route = Router::getInstance();

        if ($item !== $route->request->get($rule)) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole '.$field.' jest różne od pola '.$rule, 'field', 'field' => $field];
        }
    }

    public static function required($item, $rule, $field)
    {
        if (! isset($item)) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole jest wymagane', 'field' => $field];
        }
    }

    public static function email($item, $rule, $field)
    {
        if (! filter_var($item, FILTER_VALIDATE_EMAIL)) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole musi być typu email', 'field' => $field];
        }
    }

    public static function string($item, $rule, $field)
    {
        if (is_numeric($item)) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole musi składać się tylko z liter', 'field' => $field];
        }
    }

    public static function int($item, $rule, $field)
    {
        if (! is_numeric($item)) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole musi składać się tylko z liczb', 'field' => $field];
        }
    }

    public static function more_than_zero($item, $rule, $field)
    {
        if ((int) $item <= 0) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole musi być większe niż zero', 'field' => $field];
        }
    }

    public static function date($item, $rule, $field)
    {
        if ((bool) DateTime::createFromFormat($rule, $item) === false) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Zły format daty', 'field' => $field];
        }
    }

    public static function json($item, $rule, $field)
    {
        json_decode($item);

        if (json_last_error() !== 0) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole musi być jsonem', 'field' => $field];
        }
    }

    public static function match($item, $rule, $field)
    {
        preg_match("$rule", $item, $m);

        if (empty($m)) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Pole jest niepoprawne', 'field' => $field];
        }
    }

    public static function unique($item, $rule, $field)
    {
        if ($rule::select([$field])->where($field, '=', $item)->exist()) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Taka nazwa już istnieje', 'field' => $field];
        }
    }

    public static function check_file($item, $rule, $field)
    {
        if ($item['error'] !== UPLOAD_ERR_OK) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Plik jest uszkodzony', 'field' => $field];
        }
    }

    public static function float($item, $rule, $field)
    {
        if (! is_numeric($item) && ! is_float($item)) {
            return ['msg' => self::$lang[$field][$rule] ?? 'Wymagana wartość zmniennoprzecinkowa', 'field' => $field];
        }
    }
}