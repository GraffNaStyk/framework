<?php

namespace App\Facades\Console\Commands;

use App\Facades\Migrations\Migration;

class MigrateDown
{
	public static string $name = 'app:migrate:down';
	
	private Migration $migration;
	
	public function __construct(Migration $migration)
	{
		$this->migration = $migration;
		parent::__construct();
	}
	
	public function execute()
	{
		$this->migration->down();
	}
}
