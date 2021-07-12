<?php

namespace App\Facades\Header;

class Header
{
    const RESPONSE_CODES = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error'
    ];

    public static function set(): void
    {
        if (API && defined('API')) {
            header('Content-Type: application/json; charset=utf-8');
        } else {
            header('Content-Type: text/html; charset=utf-8');
        }

        header('X-Frame-Options: sameorigin');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Referrer-Policy: no-referrer');

        if (app('security.enabled')) {
            header('Content-Security-Policy: '.app('security.protection'));
        }

        header('Strict-Transport-Security: max-age=31536000');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Permitted-Cross-Domain-Policies: none');
    }

    public static function getAllowedOptions()
    {
        header('Access-Control-Allow-Origin', '*');
        header('Access-Control-Allow-Headers', '*');
        header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
