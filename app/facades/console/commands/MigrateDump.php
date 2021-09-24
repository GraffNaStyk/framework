<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;
use App\Facades\Migrations\Migration;

class MigrateDump extends Command
{
	public static string $name = 'app:migrate:dump';
	
	private Migration $migration;
	
	public function __construct(Migration $migration)
	{
		$this->migration = $migration;
		parent::__construct();
	}
	
	public function execute()
	{
		$this->migration->dump();
	}
}
