<?php

namespace App\Facades\Console;

use App\Facades\Url\Url;
use App\Helpers\Dir;

class Command implements CommandInterface
{
	const SUCCESS = 1;
	const FAILED  = 0;
	const ABORTED = 2;
	
	protected ArgvParser $parser;
	
	private array $backgrounds = [
		'white'      => 97,
		'red'        => 31,
		'green'      => 32,
		'yellow'     => 33,
		'blue'       => 34,
		'magenta'    => 35,
		'cyan'       => 36,
		'light grey' => 37,
	];
	
	private string $fileNamespace = '/';
	
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
	
	protected function input(string $message, string $color='white'): string
	{
		echo "\033[".$this->backgrounds[mb_strtolower($color)]."m".$message.": \033[0m";
		$handle = fopen ("php://stdin","r");
		$result = fgets($handle);
		fclose($result);
		echo "\n";

		return str_replace(["\n"], [''], $result);
	}

	protected function output(string $message, string $color='white'): Command
	{
		echo "\033[".$this->backgrounds[mb_strtolower($color)]."m".$message."\033[0m \n";
		return $this;
	}
	
	protected function close(): void
	{
		exit();
	}
	
	public function putFile(string $path, string $name, string $content): void
	{
		Dir::create(app_path($path).$this->fileNamespace);

		if (file_exists(app_path($path.$this->fileNamespace.'/'.ucfirst($name).'.php'))) {
			$this->output('File '.app_path($path.$this->fileNamespace.'/'.$name).' exist', 'red')->close();
		}

		$content = str_replace('CLASSNAME', ucfirst($name), $content);
		$tmp     = array_filter(explode('/', $this->fileNamespace));
		
		$namespace = '\\';

		foreach ($tmp as $ns) {
			$namespace .= ucfirst($ns).'\\';
		}
		
		$namespace = rtrim($namespace, '\\');
		$content   = str_replace('\\NAMESPACE', $namespace, $content);

		if (file_put_contents(app_path($path.$this->fileNamespace.'/'.ucfirst($name).'.php'), $content)) {
			$this->output(
				'File:'.app_path($path.$this->fileNamespace.'/'.ucfirst($name)).' created',
				'green'
			);

			if (Console::canCreateInterface()
				&& $this->input('Do you want to create abstraction interface for this file? Type y/n') === 'y'
			) {
				$this->createInterface(app_path($path), ucfirst($name), $namespace);
			}
		} else {
			$this->output(
				'Cannot create file: '.app_path($path.'/'.ucfirst($name)).'.php',
				'red'
			)->close();
		}
	}

	public function getFile(string $name): string
	{
		return file_get_contents(app_path('/app/facades/console/files/'.$name));
	}

	private function createInterface(string $path, string $name, string $namespace)
	{
		Dir::create($path.'/abstraction'.$this->fileNamespace);
		$interfaceName = Url::segment(Console::getCommandName(), 'end', ':');
		$content = $this->getFile('interface');
		$content = str_replace('CLASSNAME', ucfirst($name).'Interface', $content);
		$content = str_replace('NAMESPACE', ucfirst($interfaceName), $content);
		$content = str_replace('NSPATH', $namespace, $content);
		
		if (file_exists($path.'/abstraction'.$this->fileNamespace.'/'.ucfirst($name).'Interface.php')) {
			$this->output('Cannot create interface')->close();
		}

		if (file_put_contents($path.'/abstraction'.$this->fileNamespace.'/'.ucfirst($name).'Interface.php', $content)) {
			$this->output('Interface created')->close();
		}
		
		$this->output('Cannot create interface')->close();
	}
	
	protected function setNamespace(ArgvParser $argvParser): void
	{
		if ($argvParser->has('ns')) {
			$this->fileNamespace .= strtolower(trim(ltrim($argvParser->get('ns'), '/')));
		}
	}
}
