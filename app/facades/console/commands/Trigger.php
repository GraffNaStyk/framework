<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;

class Trigger extends Command
{
	public static string $name = 'app:make:trigger';
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}

	public function execute()
	{
		$this->setNamespace($this->parser);
		$name = $this->input('Please set name for trigger');
		$this->putFile('/app/trigger', $name.'Trigger', $this->getFile('trigger'));
	}
}
