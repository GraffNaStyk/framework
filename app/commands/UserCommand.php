<?php

namespace App\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;

class UserCommand extends Command
{
	public static string $name = 'app:user';
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public function configure(): void
	{
		parent::configure();
	}
	
	protected function execute(): int
	{
		$input = $this->input('Hej', 'green');
		$input = $this->input('Co robisz '.$input);
		$this->output('To papa'. $input);

		return Command::ABORTED;
	}
}
