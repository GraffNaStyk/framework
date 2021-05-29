<?php


namespace App\Facades\Console;

trait FileCreator
{
	public function putFile(string $file, string $content)
	{
		if (file_exists($file)) {
			Console::output(
				'File exist',
				'red'
			);
			exit;
		}
		
		if (file_put_contents(app_path($file), $content)) {
			Console::output(
				'File in path:' .ucfirst($this->namespace).'/'.ucfirst($this->name).'Controller.php created.',
				'green'
			);
		}
	}
}
