<?php

namespace App\Facades\Helpers;

class Password
{
    public static function make(int $length = 8, int $cost = 12): array
    {
        $password = Faker::hash($length);
        return ['string' => $password, 'hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost])];
    }

    public static function crypt(string $password, int $cost = 12): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    public static function verify($rawPassword, $hashPassword): bool
    {
        return password_verify($rawPassword, $hashPassword);
    }
}
