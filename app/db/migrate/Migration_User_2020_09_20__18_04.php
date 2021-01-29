<?php
namespace App\Db\Migrate;

use App\Facades\Faker\Hash;
use App\Facades\Migrations\Schema;
use App\Model\User;

class Migration_User_2020_09_20__18_04
{
       public string $model = 'User';

       public function up(Schema $schema)
       {
           $schema->int('id')->primary();
           $schema->varchar('name', 50)->unique();
           $schema->varchar('password', 100);
           $schema->int('phone', 9);
           $schema->varchar('city', 100);
           $schema->varchar('street', 100);
           $schema->varchar('mail', 100);
           $schema->timestamp('created_at')->implicitly('CURRENT_TIMESTAMP');
           $schema->timestamp('updated_at')->onUpdate('CURRENT_TIMESTAMP')->null();
           $schema->run();
       }

       public function down(Schema $schema)
       {
           $schema->clear();
       }
}
