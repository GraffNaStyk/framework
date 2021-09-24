<?php

namespace App\Facades\Console\Commands;

use App\Facades\Migrations\Migration;

class MigrateUp
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
