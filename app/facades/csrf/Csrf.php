<?php namespace App\Facades\Csrf;

use App\Facades\Faker\Faker;
use App\Helpers\Session;

class Csrf
{
    public static function generate()
    {
        Session::set(['csrf' =>
            Faker::string(rand(2,5)).
            Faker::int(rand(2,5)).
            Faker::string(rand(2,5)).
            Faker::int(rand(2,5)).
            Faker::string(rand(2,5)).
            Faker::int(rand(2,5)).
            Faker::string(rand(2,5)).
            Faker::int(rand(2,5)).
            Faker::string(rand(2,5)).
            Faker::int(rand(2,5)).
            Faker::string(rand(2,5)).
            Faker::int(rand(2,5))
        ]);
    }

    public static function isValid($csrf)
    {
        return (string) Session::get('csrf') === $csrf ? true : false;
    }
}
