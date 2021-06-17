<?php

namespace App\Facades\Console;

class Commands
{
    protected array $commands = [
        'Crontab' => 'time php path/to/console cron {fileName} run',
        'Controller' => 'php console controller {namespace} {controller} optional:{-v} create with views',
        'Cron' => 'php console cron {fileName}',
        'Model' => 'php console model {fileName} Table',
        'Migrate' => 'php console migrate {method - up,down,make,dump} optional:{fileName table}',
        'Middleware' => 'php console middleware {fileName}',
        'Rule' => 'php console rule {fileName}',
        'Observer' => 'php console observer {fileName}',
        'Service' => 'php console service {fileName}',
    ];

    public function __construct()
    {
        Console::output('');

        foreach ($this->commands as $title => $command) {
            Console::output("\033[32m {$title}: \033[0m {$command}");
        }

        Console::output('');
    }
}
