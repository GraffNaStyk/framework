<?php

namespace App\Db\Migrate;

use App\Facades\Migrations\Schema;

class Migration_User_2021_03_25__19_37
{
	public string $model = 'Example';

	public function up(Schema $schema)
	{
		$schema->varchar('hash', 50);
		$schema->int('receiver_id', 11);
		$schema->run();
	}

	public function down(Schema $schema)
	{
		$schema->clear();
	}
}
