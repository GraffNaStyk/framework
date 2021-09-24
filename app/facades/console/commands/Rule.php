<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;

class Rule extends Command
{
	public static string $name = 'app:make:rule';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$name = $this->input('Please set name for rule');
		$this->putFile('/app/rules', $name.'Validator', $this->getFile('rule'));
	}
}
