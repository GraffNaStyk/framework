<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;

class Model extends Command
{
	public static string $name = 'app:make:model';
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public function execute()
	{
		$this->setNamespace($this->parser);
		$name    = $this->input('Please set model name:');
		$table   = $this->input('Please set table:');
		$content = $this->getFile('model');
		$content = str_replace('TABLE', mb_strtolower($table), $content);

		$this->putFile('/app/model', ucfirst($name), $content);
	}
}
