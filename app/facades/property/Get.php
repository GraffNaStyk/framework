<?php

namespace App\Facades\Property;

class Get
{
    public static function check($method, $item)
    {
    	if (! is_array($item)) {
		    $item = explode('.', $item);
	    }
    	
	    $count = array_key_last($item);
	
	    if ($count === 0) {
		    $count = 1;
	    }
	
	    $i = 0;
	
	    while ($i <= $count) {
		    if (empty($item[$i])) {
			    return $res;
		    }
		    
		    $res = Property::exist($i === 0 ? $method : $tmp, $item, $i);
		    
		    if (! $res) {
			    return false;
		    }
		    
		    $tmp = $res;
		    $i++;
	    }
	    
	    return $tmp;
    }
}
