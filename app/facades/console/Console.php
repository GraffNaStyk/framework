<?php

namespace App\Facades\Console;

use App\Facades\Dependency\Container;
use App\Facades\Dependency\ContainerBuilder;
use ReflectionClass;

class Console
{
	const FACADE_COMMAND_DIR       = '/app/facades/console/commands';
	const COMMAND_DIR              = '/app/commands';
	const COMMAND_NAMESPACE        = 'App\\Commands\\';
	const FACADE_COMMAND_NAMESPACE = 'App\\Facades\\Console\\Commands\\';
	
	private ArgvParser $parser;

	private ContainerBuilder $builder;
	
	public function __construct(ArgvParser $argvParser)
	{
		$this->parser  = $argvParser;
		$this->builder = new ContainerBuilder(new Container());
		$this->builder->container->add(ArgvParser::class, $this->parser);

		$this->parser->parse();
		$this->configure();
	}

	private function configure(): void
	{
		$objects = [
			...array_diff(scandir(app_path(self::COMMAND_DIR)), ['.', '..']),
			...array_diff(scandir(app_path(self::FACADE_COMMAND_DIR)), ['.', '..']),
		];
		
		foreach ($objects as $object) {
			if ((bool) strpos($object, 'Command')) {
				$object = self::COMMAND_NAMESPACE.str_replace('.php', '', $object);
			} else {
				$object = self::FACADE_COMMAND_NAMESPACE.str_replace('.php', '', $object);
			}

			if (property_exists($object, 'name') && $this->parser->has($object::$name)) {
				$this->parser->remove($object::$name);

				$reflector = new ReflectionClass($object);

				if ($reflector->hasMethod('__construct')) {
					$constructorParams = $this->builder->reflectConstructorParams(
						$reflector->getConstructor()->getParameters()
					);
				}

				call_user_func_array([$reflector, 'newInstance'], $constructorParams ?? []);

				break;
			}
		}
	}
}
