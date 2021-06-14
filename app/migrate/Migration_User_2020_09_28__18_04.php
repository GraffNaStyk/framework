<?php

namespace App\Migrate;

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
			])->exec();
			$schema->run();
		}
	}
	
	public function down(Schema $schema)
	{
		$schema->clear();
	}
}
