<?php

namespace App\Migrate;

use App\Facades\Migrations\Schema;

class Migration_Right_2020_09_24__22_26
{
	public string $model = 'Right';
	
	public function up(Schema $schema)
	{
		$schema->int('id', 11)->primary();
		$schema->int('user_id', 11)->unique();
		$schema->run();
	}
	
	public function down(Schema $schema)
	{
		$schema->clear();
	}
}
