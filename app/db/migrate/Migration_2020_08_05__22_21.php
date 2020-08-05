<?php
namespace App\Db\Migrate;

use App\Facades\Migrations\Schema;

class Migration_2020_08_05__22_21
{
       public string $model = 'Example';

       public function up(Schema $schema)
       {
           $schema->int('id')->primary();
           $schema->varchar('name', 20)->index();
           $schema->varchar('email')->unique();
           $schema->run();
       }

       public function down(Schema $schema)
       {
           $schema->clear();
       }
}
