<?php

namespace App\Facades\Cache;

use App\Helpers\Dir;

class Cache
{
    public static function remember(int $seconds, string $path, \Closure $closure)
    {
    	Dir::create(storage_path('/private/cache'.$path));
	
	    $route     = Router::getInstance()->routeParams();
	    $cacheName = sha1(
		    $route['namespace'].'\\'.$route['controller'].'\\'.$route['action'].'\\'.implode('\\', $route['params'])
	    );
	    
        $dateEnd = filemtime(storage_path('/private/cache'.$path.'/'.$cacheName));
        $dateEnd = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', $dateEnd).'+ '.$seconds.' sec'));

        if (file_exists(storage_path('/private/cache'.$path.'/'.$cacheName))
            && $dateEnd > date('Y-m-d H:i:s')
        ) {
            return unserialize(file_get_contents(storage_path('/private/cache'.$path.'/'.$cacheName)));
        } else {
            $result = $closure();
            file_put_contents(storage_path('/private/cache'.$path.'/'.$cacheName), serialize($result));
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
