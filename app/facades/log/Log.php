<?php

namespace App\Facades\Log;

use App\Facades\Http\Router\Router;
use App\Helpers\Storage;

class Log
{
    private static function make(string $type, array $data): void
    {
    	$log  = '['.date('Y-m-d H:i:s').'] [url]: '.Router::url().' [client]: '.php_sapi_name();
	    $log .= ' [ip]: '.getenv('REMOTE_ADDR'). ' [host]: '.gethostbyaddr(getenv('REMOTE_ADDR')).' '.PHP_EOL;
	    $log .= json_encode($data, JSON_PRETTY_PRINT);
	    
        Storage::disk('private')->make('logs/'.$type);
        
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
    
    public static function info(array $data): void
    {
        static::make('info', $data);
    }
    
    public static function custom(string $name, array $data): void
    {
        static::make($name, $data);
    }
    
    public static function handleError(): void
    {
        $lastError = error_get_last();
        
        if (! empty($lastError) && in_array($lastError['type'], [E_USER_ERROR, E_ERROR, E_PARSE])) {
            header("HTTP/1.0 500 Internal Server Error");
            http_response_code(500);

            if (app('dev') === false) {
                static::make('php', $lastError);
                exit (require_once view_path('errors/fatal.php'));
            } else {
                pd($lastError, true);
            }
        }
    }
    
    public static function setDisplayErrors(): void
    {
	    if (app('dev')) {
		    ini_set('display_startup_errors', 1);
		    error_reporting(E_ERROR | E_PARSE);
	    } else {
		    ini_set('display_startup_errors', 0);
		    error_reporting(0);
	    }
    }
}
