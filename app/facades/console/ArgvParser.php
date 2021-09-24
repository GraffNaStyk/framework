<?php

namespace App\Facades\Console;

use App\Facades\Property\PropertyFacade;

class ArgvParser
{
	use PropertyFacade;
	
	const MAX_ARGV = 50;
	
	protected array $argv;
	
	public function __construct(array $argv)
	{
		$this->argv = $argv;
	}
	
	public function parse(): void
	{
		$index   = 0;
		$configs = [];
		
		while ($index < self::MAX_ARGV && isset($this->argv[$index])) {
			if (preg_match('/^([^-\=]+.*)$/', $this->argv[$index], $matches) === 1) {
				$configs[$matches[1]] = true;
			} else if (preg_match('/^-+(.+)$/', $this->argv[$index], $matches) === 1) {
				if (preg_match('/^-+(.+)\=(.+)$/', $this->argv[$index], $subMatches) === 1) {
					$configs[$subMatches[1]] = $subMatches[2];
				} else if (isset($this->argv[$index + 1]) && preg_match('/^[^-\=]+$/', $this->argv[$index + 1]) === 1) {
					$configs[$matches[1]] = $this->argv[$index + 1];
					$index++;
				} elseif (strpos($matches[0], '--') === false) {
					for ($j = 0; $j < strlen($matches[1]); $j += 1) {
						$configs[$matches[1][$j]] = true;
					}
				} else if (isset($this->argv[$index + 1]) && preg_match('/^[^-].+$/', $this->argv[$index + 1]) === 1) {
					$configs[$matches[1]] = $this->argv[$index + 1];
					$index++;
				} else {
					$configs[$matches[1]] = true;
				}
			}
			$index++;
		}

		array_shift($configs);
		
		$this->setParams($configs);
	}
}
