<?php

namespace App\Cron;

use App\Facades\Faker\Faker;
use App\Model\Client;

class CreateUserCron
{
    public function __construct()
    {
        Client::insert([
            'name' => Faker::string(10),
            'www' => Faker::string(10),
            'ftp_server' => Faker::string(10),
            'ftp_user' => Faker::string(10),
            'ftp_password' => Faker::string(10),
            'db_link' => Faker::string(10),
            'db_user' => Faker::string(10),
            'db_password' => Faker::string(10),
            'db_name' => Faker::string(10),
        ])->exec();
    }
}
