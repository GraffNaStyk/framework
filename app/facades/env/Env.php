<?php

namespace App\Facades\Env;

class Env
{
    public static function set(): array
    {
        $env = file_get_contents(app_path('app/config/.env'));
        $environment = [];

        foreach (array_filter(explode(PHP_EOL, $env)) as $item) {
            $item = explode('=', $item);
            $environment[trim($item[0])] = trim($item[1]);
        }

        return $environment;
    }
}