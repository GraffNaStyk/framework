<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\ArgvParser;
use App\Facades\Console\Command;
use App\Helpers\Dir;

class Controller extends Command
{
	public static string $name = 'app:make:controller';
	
	private array $views = [
		'index.twig',
		'add.twig',
		'edit.twig',
		'show.twig'
	];
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser = $argvParser;
		parent::__construct();
	}
	
	public function execute()
	{
		$ns = $this->input('Please set namespace:');

		if ((string) $ns === '') {
			$this->output('Namespace must be not empty')->close();
		}
		
		$name    = $this->input('Please set name of controller');
		$content = $this->getFile('controller');
		$content = str_replace('PATH', ucfirst($ns), $content);
		
		$this->putFile('/app/controllers/'.$ns, ucfirst($name).'Controller', $content);
		
		if ($this->parser->has('view') && (int) $this->parser->get('view') === 1) {
			$this->makeViews($ns, $name);
		}
	}
	
	private function makeViews(string $namespace, string $name): void
	{
		Dir::create(view_path(strtolower($namespace).'/'.strtolower($name)));
		
		foreach ($this->views as $view) {
			if (! file_exists(view_path(strtolower($namespace).'/'.strtolower($name).'/'.$view))) {
				file_put_contents(
					view_path(strtolower($namespace).'/'.strtolower($name).'/'.$view),
					file_get_contents(app_path('/app/facades/files/view'))
				);
			}
		}
	}
}
