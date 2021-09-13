<?php

namespace App\Facades\Db;

use App\Facades\Config\Config;
use App\Facades\Http\Router\Router;
use ReflectionClass;

class TriggerResolver
{
    public static function resolve(string $object, string $method, Db $db)
    {
        $object = Config::get('app.triggers_path').ucfirst($object).'Trigger';
		
        if (class_exists($object)) {
	        $reflector = new ReflectionClass($object);
	
	        if ($reflector->hasMethod('__construct')) {
	        	$container = Router::getInstance()->getContainer();
		        $container->container->replace(get_class($db), $db);

		        $constructorParams = $container->reflectConstructorParams(
			        $reflector->getConstructor()->getParameters()
		        );
	        }
	
	        (call_user_func_array([$reflector, 'newInstance'], $constructorParams ?? []))->{$method}();
        }
    }
}
