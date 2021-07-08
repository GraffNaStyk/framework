<?php

namespace App\Facades\Http;

class Response
{
    public static function json($response, $status = 200, $headers = []): string
    {
        self::setHeaders($headers);
        http_response_code($status);
        return json_encode($response);
    }

    private static function setHeaders(array $headers = []): void
    {
        if (! empty($headers) && ! headers_sent()) {
            foreach ($headers as $key => $header) {
                header("$key: $header");
            }
        } else if (! headers_sent()) {
            header('Content-Type: application/json');
            header('Cache-Control: no-cache, must-revalidate');
        }
    }
}
