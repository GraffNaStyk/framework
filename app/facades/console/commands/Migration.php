<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;

class Migration extends Command
{
	public static string $name = 'app:make:migration';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$name = $this->input('Please set model name for migration');
		$nameName = 'Migration_'.ucfirst($name).'_'.date('Y_m_d__H_i_s');
		
		$migration = $this->getFile('migration');
		$migration = str_replace('CLASSNAME', $nameName, $migration);
		$migration = str_replace('MODEL', $name, $migration);
		
		$this->putFile('/app/migrate', $nameName, $migration);
	}
}
