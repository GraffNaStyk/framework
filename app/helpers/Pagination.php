<?php

namespace App\Helpers;

use App\Core\App;
use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Facades\Url\Url;

class Pagination
{
    public static function make(string $model, int $page, string $url)
    {
    	$alias = Url::full().'/'.Router::getAlias();
		$total = ceil($model::count('id')->get()->count / App::PER_PAGE);
		
        View::set([
            'pagination' => [
                'previousLink' => $alias.$url.'/'.($page-1),
                'nextLink'     => $alias.$url.'/'.($page+1),
                'previous'     => ($page-1),
                'next'         => ($page+1),
                'currentPage'  => ($page),
                'total'        => $total,
	            'first'        => $alias.$url,
	            'last'         => $alias.$url.'/'.$total,
	            'current'      => $alias.$url.'/'.($page)
            ]
        ]);
    }
}
