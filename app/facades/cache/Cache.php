<?php

namespace App\Facades\Cache;

use App\Facades\Http\Router\Router;

class Cache
{
    public static function remember(int $seconds, callable $query)
    {
        $route = Router::getInstance()->routeParams();
        $cacheName = sha1(
            $route['namespace'].'\\'.$route['controller'].'\\'.$route['action'].'\\'.implode('\\', $route['params'])
        );

        $dateEnd = filemtime(storage_path('private/cache/'.$cacheName.'.json'));
        $dateEnd = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', $dateEnd).'+ '.$seconds.' sec'));

        if (file_exists(storage_path('private/cache/'.$cacheName.'.json'))
            && $dateEnd > date('Y-m-d H:i:s')
        ) {
            return json_decode(file_get_contents(storage_path('private/cache/'.$cacheName.'.json')));
        } else {
            $result = $query();
            file_put_contents(storage_path('private/cache/'.$cacheName.'.json'), json_encode($result));
        }

        return $result;
    }
    
    public static function clear(int $olderThanSeconds, array $files = []): void
    {
    	if (empty($files)) {
		    $files = glob(storage_path('/private/cache/*'));
	    }
	
	    $date = date('Y-m-d H:i:s', strtotime('+ '.$olderThanSeconds.' sec'));

    	foreach ($files as $file) {
    		if (filemtime($file) >= $date) {
    			unlink($file);
		    }
	    }
    }
}
