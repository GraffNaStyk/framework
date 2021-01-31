<?php

namespace App\Helpers;

use App\Core\Controller;
use App\Facades\Http\View;

class Pagination
{
    public static function make(string $model, int $page, string $url)
    {
        View::set([
            'pagination' => [
                'previous' => $url.($page-1),
                'next'     => $url.($page+1),
                'pages' => ceil($model::count('id')->get()['id'] / Controller::PER_PAGE)
            ]
        ]);
    }
}
