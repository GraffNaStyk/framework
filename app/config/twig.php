<?php

use App\Facades\Http\Request;

return [
	'globals' => [
		'isAjax' => (int) Request::isAjax(),
		'url'    => \App\Facades\Url\Url::full(),
	],
	'cache_view' => false
];
