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
		if (! $this->parser->has('ns')) {
			$this->output('please define namespace using -ns=NAMESPACE')->close();
		}
		
		$this->setNamespace($this->parser);
		$name = $this->input('Please set name of controller');

		$this->putFile('/app/controllers', ucfirst($name).'Controller', $this->getFile('controller'));
		
		if ($this->parser->has('view') && (int) $this->parser->get('view') === 1) {
			$this->makeViews(str_replace($name, '', $this->parser->get('ns')), $name);
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
