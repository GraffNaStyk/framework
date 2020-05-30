<?php

namespace App\Facades\Validator;

use App\Helpers\Session;
use App\Facades\Http\View;

class Validator
{
    protected static array $rules = [];
    private static array $validatorErrors = [];

    public static function make($request, array $rules): bool
    {
        static::refactorRules($rules);
        static::run($request);

        if(!empty(static::$validatorErrors = array_filter(static::$validatorErrors))) {
            if(!View::isAjax()) {
                Session::checkIfDataHasBeenProvided($request);
                Session::msg(static::$validatorErrors, 'danger');
            }
            return false;
        }
        return true;
    }

    private static function refactorRules(array $rules)
    {
        foreach ($rules as $key => $rule) {
            $eachRule = explode('|', $rule);
            foreach ($eachRule as $rulesValue) {
                $rulesValue = explode(':', $rulesValue);
                static::$rules[$key][$rulesValue[0]] = $rulesValue[1] ?? $rulesValue[0];
            }
        }
    }

    private static function run(array $request)
    {
        foreach (static::$rules as $key => $item) {
            if(array_key_exists($key, $request)) {
                foreach ($item as $fnName => $validateRule) {
                    static::$validatorErrors[] = Rules::$fnName($request[$key], $validateRule, $key);
                }
            } else {
                static::$validatorErrors[] = ['msg' => 'Pole jest wymagane', 'field' => $key];
            }
        }
    }

    public static function getErrors(): array
    {
        return array_values(static::$validatorErrors);
    }
}
