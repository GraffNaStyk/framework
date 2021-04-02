<?php

namespace App\Facades\Http;

abstract class Response
{
    public static function json($response, $status = 200, $headers = []): void
    {
        self::setHeaders($headers);
        self::setCode($status);
        echo json_encode($response, true);
        die();
    }

    private static function setCode(int $code): void
    {
        http_response_code($code);
    }
    
    private static function setHeaders(array $headers = []): void
    {
        if (! empty($headers) && ! headers_sent()) {
            foreach ($headers as $key => $header) {
	            header("$key: $header");
            }
        } else if (! headers_sent()) {
            header('Content-Type: application/json');
            header("Cache-Control: no-cache, must-revalidate");
        }
    }
}
