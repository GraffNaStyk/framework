<?php


namespace App\Facades\Console;

trait FileCreator
{
    public function putFile(string $file, string $content)
    {
        if (file_exists(app_path($file))) {
            Console::output('File '.app_path($file).' exist', 'red');
            exit;
        }

        if (file_put_contents(app_path($file), $content)) {
            Console::output(
                'File in path:'.app_path($file).' created',
                'green'
            );
        } else {
	        Console::output(
		        'Cannot create file: '.app_path($file),
		        'red'
	        );
        }
    }
}
