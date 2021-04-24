<?php

namespace App\Db\Migrate;

use App\Facades\Migrations\Schema;

class Migration_User_2020_09_20__18_04
{
	public string $model = 'User';
	
	public function up(Schema $schema)
	{
		$schema->int('id')->primary();
		$schema->varchar('name', 50)->unique();
		$schema->varchar('password', 100);
		$schema->timestamp('created_at')->implicitly('CURRENT_TIMESTAMP');
		$schema->timestamp('updated_at')->onUpdate('CURRENT_TIMESTAMP')->null();
		$schema->run();
	}
	
	public function down(Schema $schema)
	{
		$schema->clear();
	}
}
