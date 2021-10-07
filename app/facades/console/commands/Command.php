<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;

class Command extends \App\Facades\Console\Command
{
	public static string $name = 'app:make:command';
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public static function getDescription(): string
	{
		return 'Create new console command, for namespace set -ns=path/to/namespace';
	}
	
	public function execute()
	{
		$this->setNamespace($this->parser);
		$name = $this->input('Please set name for command');
		$this->putFile('/app/commands', $name.'Command', $this->getFile('command'));
	}
}
