<?php

namespace App\Facades\Log;

class LogErrorFormatter
{
	private int $errorType;
	private string $message;
	private string $file;
	private int $line;
	private string $trace;
	
	public function __construct(array $log)
	{
		$this->errorType = $log['type'];
		$this->message   = $log['message'];
		$this->file      = $log['file'];
		$this->line      = $log['line'];
		$this->parseMessage();
	}
	
	public function format()
	{
		echo '<style>
        * {
            background: #252e39;
            color: #fff;
            font-family: \'Nunito\', sans-serif;
            text-align: justify;
            overflow: hidden;
            font-weight: bold;
        }</style>';
		echo '<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">';
		echo '<p style="line-height: 30px; text-align: justify">'.$this->message.'</p>';
		echo '<p> Stack trace: </p>';
		pd($this->trace, false);
		pd('File: &nbsp;'. $this->file.':'.$this->line);
	}
	
	private function parseMessage(): void
	{
		$tmp           = explode('Stack trace:', $this->message);
		$this->trace   = str_replace('thrown', '', $tmp[1]);
		$this->message = trim($tmp[0]);
		$this->message = str_replace('and defined in '.$this->file.':'.$this->line, '', $this->message);
	}
}
