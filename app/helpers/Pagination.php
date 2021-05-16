<?php

namespace App\Helpers;

use App\Core\App;
use App\Facades\Http\View;

class Pagination
{
    public static function make(string $model, int $page, string $url)
    {
        View::set([
            'pagination' => [
                'previous' => $url.($page-1),
                'next'     => $url.($page+1),
                'total'    => ceil($model::count('id')->get()->total / App::PER_PAGE)
            ]
        ]);
    }
}
