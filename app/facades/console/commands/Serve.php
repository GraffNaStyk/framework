<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;

class Serve extends Command
{
	public static string $name = 'app:serve';
	
	private string $host = 'localhost';
	
	private int $port = 8000;
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public function execute()
	{
		if ($this->parser->has('p')) {
			$this->port = $this->parser->get('p');
		}
		
		if ($this->parser->has('h')) {
			$this->host = 'http://'.$this->parser->get('h');
		}
		
		exec("php -S {$this->host}:{$this->port} index.php");
	}
}
