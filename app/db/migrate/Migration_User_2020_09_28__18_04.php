<?php

namespace App\Db\Migrate;

use App\Facades\Faker\Faker;
use App\Facades\Faker\Hash;
use App\Facades\Faker\Password;
use App\Facades\Migrations\Schema;
use App\Model\User;

class Migration_User_2020_09_28__18_04
{
       public string $model = 'User';

       public function up(Schema $schema)
       {
           if (! $schema->hasRecord('users', 'name', 'Graff')) {
               User::insert([
                   'name' => 'Graff',
                   'password' => Password::crypt('mulias123'),
                   'phone' => Faker::int(9),
                   'city' => Faker::string(7),
                   'street' => Faker::string(7),
                   'mail' => Faker::string(12),
               ])->exec();
               $schema->run();
           }
       }

       public function down(Schema $schema)
       {
           $schema->clear();
       }
}
