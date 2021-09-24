<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;
use App\Facades\Migrations\Migration;

class MigrateUp extends Command
{
	public static string $name = 'app:migrate:up';
	
	private Migration $migration;
	
	public function __construct(Migration $migration)
	{
		$this->migration = $migration;
		parent::__construct();
	}
	
	public function execute()
	{
		$this->migration->up();
	}
}
