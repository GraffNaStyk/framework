<?php

namespace App\Facades\Log;

use App\Facades\Http\Router\Router;
use App\Facades\Storage\Storage;

class Log
{
    private static function make(string $type, array $data): void
    {
        $log = '['.date('Y-m-d H:i:s').'] [url]: '.Router::url().' [client]: '.php_sapi_name();
        $log .= ' [ip]: '.$_SERVER['REMOTE_ADDR'].' [host]: '.gethostbyaddr($_SERVER['REMOTE_ADDR']).' ';
        $log .= json_encode($data, JSON_PRETTY_PRINT);

        Storage::private()->make('logs/'.$type);

        file_put_contents(
            storage_path('private/logs/'.$type.'/'.date('Y-m-d').'.log'),
            $log.PHP_EOL,
            FILE_APPEND
        );
    }

    public static function sql(array $data): void
    {
        static::make('sql', $data);
    }

    public static function info(string $message, array $data = []): void
    {
        static::make('info', ['msg' => $message, $data]);
    }

    public static function custom(string $name, array $data): void
    {
        static::make($name, $data);
    }
}
