<?php

namespace app\facades\log;

use App\Facades\Http\Router;

class Log
{
    private static function make(string $type, array $data): void
    {
        $data['trace'] = [
            'controller' => Router::getNamespace().'\\'.Router::getClass(),
            'action' => Router::getAction()
        ];
        
        $data['client'] = php_sapi_name();
        $data['text'] = 'Log '.$type.' message';
        $data['date'] = date('Y-m-d H:i:s');
        
        file_put_contents(
            storage_path('private/logs/'.$type.'_' . date('d-m-Y') . '.log'),
            json_encode(array_reverse($data), JSON_PRETTY_PRINT),
            FILE_APPEND
        );
        
    }
    
    public static function sql(array $data)
    {
        static::make('sql', $data);
    }
    
    public static function info(array $data)
    {
        static::make('info', $data);
    }
    
    public static function handlePhpError()
    {
        $lastError = error_get_last();

        if (! empty($lastError)) {
            if ($lastError['type'] === E_ERROR || $lastError['type'] === E_USER_ERROR || $lastError['type'] === E_PARSE) {
                header("HTTP/1.0 500 Internal Server Error");
                http_response_code(500);
            
                if (php_sapi_name() === 'cli') {
                    print_r($lastError);
                    exit;
                }
                
                if (app('dev') === false) {
                    static::make('php', $lastError);
                    exit (require_once view_path('errors/fatal.php'));
                } else {
                    exit (require_once view_path('errors/fatal-dev.php'));
                }
            }
        }
    }
}
