<?php

namespace App\Facades\Cache;

use App\Facades\Http\Router\Router;

class Cache
{
	public static function remember(int $minutes, callable $query)
	{
		$route     = Router::getInstance()->routeParams();
		$cacheName = sha1(
			$route['namespace'].'\\'.$route['controller'].'\\'.$route['action'].'\\'.implode('\\', $route['params'])
		);

		$dateEnd = filemtime(storage_path('private/cache/'.$cacheName.'.json'));
		$dateEnd = date('Y-m-d H:i', strtotime(date('Y-m-d H:i', $dateEnd).'+ '.$minutes.' minute'));

		if (file_exists(storage_path('private/cache/'.$cacheName.'.json'))
			&& $dateEnd > date('Y-m-d H:i')
		) {
			return json_decode(file_get_contents(storage_path('private/cache/'.$cacheName.'.json')));
		} else {
			$result = $query();
			file_put_contents(storage_path('private/cache/'.$cacheName.'.json'), json_encode($result));
		}

		return $result;
	}
}
