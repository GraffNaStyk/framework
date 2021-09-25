<?php

namespace App\Facades\Console;

use App\Helpers\Dir;

class Command implements CommandInterface
{
	const SUCCESS = 1;
	const FAILED  = 0;
	const ABORTED = 2;
	
	protected ArgvParser $parser;
	
	private array $backgrounds = [
		'black' => 40,
		'red' => 41,
		'green' => 42,
		'yellow' => 43,
		'blue' => 44,
		'magenta' => 45,
		'cyan' => 46,
		'light grey' => 47,
	];
	
	public function __construct()
	{
		$this->configure();
	}
	
	public function configure(): void
	{
		ini_set('display_startup_errors', 1);
		error_reporting(E_ERROR | E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_PARSE);
		
		if (! method_exists($this, 'execute')) {
			$this->output('Missing execute method!', 'red')->close();
		}
		
		$this->execute();
	}
	
	protected function input(string $message, string $color='black'): string
	{
		echo "\033[".$this->backgrounds[mb_strtolower($color)]."m".$message."\n";
		$handle = fopen ("php://stdin","r");
		$result = fgets($handle);
		fclose($result);

		return str_replace(["\n"], [''], $result);
	}
	
	protected function output(string $message, string $color='black'): Command
	{
		echo "\033[".$this->$backgrounds[mb_strtolower($color)]."m".$message."\n";
		return $this;
	}
	
	protected function close(): void
	{
		exit();
	}
	
	public function putFile(string $path, string $name, string $content): void
	{
		Dir::create(app_path($path));

		if (file_exists(app_path($path.'/'.$name.'.php'))) {
			$this->output('File '.app_path($path.'/'.$name).' exist', 'red')->close();
		}

		$content = str_replace('CLASSNAME', ucfirst($name), $content);

		if (file_put_contents(app_path($path.'/'.ucfirst($name).'.php'), $content)) {
			$this->output(
				'File:'.app_path($path.'/'.ucfirst($name)).' created',
				'green'
			);
		} else {
			$this->output(
				'Cannot create file: '.app_path($path.'/'.ucfirst($name)).'.php',
				'red'
			)->close();
		}
	}

	public function getFile(string $name): string
	{
		return file_get_contents(app_path('/app/facades/files/'.$name));
	}
}
