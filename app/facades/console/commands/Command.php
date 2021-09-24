<?php

namespace App\Facades\Console\Commands;

class Command extends \App\Facades\Console\Command
{
	public static string $name = 'app:make:command';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$name = $this->input('Please set name for command');
		$this->putFile('/app/commands', $name.'Command', $this->getFile('command'));
	}
}
