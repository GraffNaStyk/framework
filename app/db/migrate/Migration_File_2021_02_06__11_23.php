<?php

namespace App\Db\Migrate;

use App\Facades\Migrations\Schema;

class Migration_File_2021_02_06__11_23
{
	public string $model = 'File';
	
	public function up(Schema $schema)
	{
		$schema->int('id')->primary()->unsigned();
		$schema->varchar('name', 100)->index();
		$schema->varchar('hash', 100)->index();
		$schema->varchar('dir');
		$schema->varchar('ext', 6);
		$schema->varchar('sha1', 100);
		$schema->enum('visible', ['y', 'n'])->default('y');
		$schema->run();
	}
	
	public function down(Schema $schema)
	{
		$schema->clear();
	}
}
