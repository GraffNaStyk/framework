<?php namespace App\Facades\Faker;

class Hash
{
    public static function make(int $length = 8, int $cost = 12):array
    {
        $password = '';
        while (strlen($password) < $length) {
            $password .= Faker::string(1);
            $password .= Faker::int(1);
        }
        
        return ['string' => $password, 'hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost])];
    }
    
    public static function crypt(string $password, int $cost = 12):string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
    
    public static function verify($rawPassword, $hashPassword)
    {
        return password_verify($rawPassword, $hashPassword);
    }
}
