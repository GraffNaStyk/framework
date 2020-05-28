<?php namespace App\Facades\Faker;

class Faker
{
    private static array $letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'w', 'x', 'y', 'z'];
    private static array $numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    public static function string(int $length = 0): string
    {
        $string = null;

        for ($i = 0; $i < $length; $i++)
            $string .= static::$letters[rand(0,count(static::$letters)-1)];

        return  $string;
    }

    public static function int(int $length = 0): int
    {
        $number = null;

        for ($i = 0; $i < $length; $i++)
            $number .= static::$numbers[rand(0,count(static::$numbers)-1)];

        return $number;
    }
}
