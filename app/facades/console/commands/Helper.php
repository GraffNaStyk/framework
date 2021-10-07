<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;

class Helper extends Command
{
	public static string $name = 'app:make:helper';
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public function execute()
	{
		$this->setNamespace($this->parser);
		$name = $this->input('Please set name for helper');
		$this->putFile('/app/helpers', $name, $this->getFile('helper'));
	}
}
