<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;

class Event extends Command
{
	public static string $name = 'app:make:event';
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public function execute()
	{
		$this->setNamespace($this->parser);
		$name = $this->input('Please set name for event');
		$this->putFile('/app/events', $name.'Event', $this->getFile('event'));
	}
}
