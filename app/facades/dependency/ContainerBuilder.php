<?php

namespace App\Facades\Dependency;
use App\Helpers\Arr;
use ReflectionClass;

class ContainerBuilder
{
	public Container $container;
	
	public function __construct(Container $container)
	{
		$this->container = $container;
	}
	
	public function reflectConstructorParams(array $reflectionParams): array
	{
		$combinedParams = [];
		
		foreach ($reflectionParams as $key => $refParam) {
			$class = $refParam->getClass()->name;
			
			if (! empty($class)) {
				$reflector = new ReflectionClass($class);
				
				if ($reflector->isInterface() && Arr::has((array) config('interfaces'), $reflector->getName())) {
					$reflector = new ReflectionClass(Arr::get(config('interfaces'), $reflector->getName()));
				} else if ($reflector->isInterface()) {
					throw new \LogicException($reflector->getName().' is not register in interfaces.php');
				}
				
				if (! $this->container->has($class)) {
					if ($reflector->hasMethod('__construct')) {
						$params = $this->reflectConstructorParams($reflector->getConstructor()->getParameters());
						$this->container->add($class, call_user_func_array([$reflector, 'newInstance'], $params ?? []));
					} else {
						$this->container->add($class, new $class());
					}
				}

				$combinedParams[$key] = $this->container->get($class);
				unset($reflector);
			}
		}
		
		return $combinedParams;
	}
}
