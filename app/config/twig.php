<?php

use App\Facades\Http\Request;
use App\Facades\Http\Session;

return [
	'globals' => [
		'isAjax' => ((int) Request::isAjax() || Session::get('beAjax')),
		'url'    => \App\Facades\Url\Url::full()
	],
	'cache_view' => false
];
