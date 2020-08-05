<?php
namespace App\Db\Migrate;

use App\Facades\Migrations\Schema;

class Migration_2020_07_27__22_58
{
       public string $model = 'User';

       public function up(Schema $schema)
       {
           $schema->int('test')->unique();
           $schema->run();
       }

       public function down(Schema $schema)
       {
           $schema->clear();
       }
}
