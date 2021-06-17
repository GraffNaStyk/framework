<?php

namespace App\Facades\Validator;

use App\Facades\Http\Request;
use App\Facades\Http\Session;

class Validator
{
    protected static array $rules = [];
    private static array $validatorErrors = [];

    public static function make($request, array $rules): bool
    {
        static::$validatorErrors = [];
        static::$rules = [];
        static::refactorRules($rules);
        static::run($request);

        if (! empty(static::$validatorErrors = array_filter(static::$validatorErrors))) {
            if (! Request::isAjax()) {
                Session::checkIfDataHasBeenProvided($request);
                Session::msg(static::$validatorErrors, 'danger');
            }

            return false;
        }

        return true;
    }

    private static function refactorRules(array $rules)
    {
        if (isset($rules['lang'])) {
            Rules::$lang = $rules['lang'];
            unset($rules['lang']);
        }

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
            foreach ($item as $fnName => $validateRule) {
                if (method_exists($fnName, $validateRule)) {
                    static::$validatorErrors[] = $fnName::$validateRule($request[$key], $key);
                } else {
                    if (((string) $request[$key] === '' && isset($item['required'])) || (string) $request[$key] !== '') {
                        if (method_exists(Rules::class, $fnName)) {
                            static::$validatorErrors[] = Rules::$fnName($request[$key], $validateRule, $key);
                        }
                    }
                }
            }
        }
    }

    public static function getErrors(): array
    {
        return array_values(static::$validatorErrors);
    }
}