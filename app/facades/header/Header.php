<?php

namespace App\Facades\Header;

class Header
{
    public static function set(): void
    {
        header("Content-Type: text/html; charset=utf-8");
        header("X-Frame-Options: sameorigin");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Referrer-Policy: no-referrer');
        header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline' fonts.googleapis.com; font-src 'self' fonts.gstatic.com; img-src 'self' data:");
        header('Strict-Transport-Security: max-age=31536000');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Permitted-Cross-Domain-Policies: none');
    }
}
