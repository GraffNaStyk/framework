<?php

namespace App\Facades\Log;

class Log
{
    private static function make(string $type, array $data): void
    {
        $data['client'] = php_sapi_name();
        $data['date'] = date('Y-m-d H:i:s');
    
        $content = file_get_contents(storage_path('private/logs/' . $type . '_' . date('Y-m-d') . '.json'));
    
        if ((string) $content !== '') {
            $content = json_decode($content, true);
            $content[] = array_reverse($data);
            file_put_contents(
                storage_path('private/logs/' . $type . '_' . date('Y-m-d') . '.json'),
                json_encode($content, JSON_PRETTY_PRINT)
            );
        } else {
            file_put_contents(
                storage_path('private/logs/' . $type . '_' . date('Y-m-d') . '.json'),
                json_encode([array_reverse($data)], JSON_PRETTY_PRINT)
            );
        }
    }
    
    public static function sql(array $data)
    {
        static::make('sql', $data);
    }
    
    public static function info(array $data)
    {
        static::make('info', $data);
    }
    
    public static function custom(string $name, array $data)
    {
        static::make($name, $data);
    }
    
    public static function handlePhpError()
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
}
