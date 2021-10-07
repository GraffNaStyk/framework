<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;

class Rule extends Command
{
	public static string $name = 'app:make:rule';
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public function execute()
	{
		$this->setNamespace($this->parser);
		$name = $this->input('Please set name for rule');
		$this->putFile('/app/rules', $name.'Validator', $this->getFile('rule'));
	}
}
