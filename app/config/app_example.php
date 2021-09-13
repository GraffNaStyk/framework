<?php

return [

    /**
     * @csrf is used to blocking csrf attack from users,
     *  if this variable is set to true, you need to add for every form
     *  twig variable like {{ form.csrf('Controller@action') }}
     */
    'csrf' => true,

    /**
     * @dev here tou can set developer mode to true or false, if developer mode
     *  is set to true on page have all bugs, if not all logs send to
     *  storage/private/logs like php or sql log.
     **/
    'dev' => true,

    /**
     * @url this is a framework url, default u can set '/' if framework exist
     *  in any sub folder need to add this path there to good working
     **/
    'url' => '/',

    /**
     * @cache_view disable or enable view caching
     **/
    'cache_view' => false,

    /**
     * @mail configuration using in framework to send mails.
     *  If array values are empty mail are not configured.
     **/
    'mail' => [
        'smtp' => '',
        'user' => '',
        'password' => '',
        'port' => '',
        'from' => '',
        'fromName' => ''
    ],

    /**
     * @Always loaded libraries css / js from main css / js directory
     *
     **/
    'is_loaded' => [
        'css' => [
            'bootstrap', 'slim-select', 'loader', 'font-awesome.min'
        ],

        'js' => [
            'App', 'bootstrap', 'slim-select'
        ]
    ],

    /**
     * @Security used for header Content-Security-Policy
     *
     **/
    'security' => [
        'enabled' => true,
        'protection' =>
            "default-src 'self'; style-src 'self' 'unsafe-inline' fonts.googleapis.com; font-src 'self' fonts.gstatic.com; img-src 'self' data:"

    ],
	
	/**
	 * @Middlewares namespace for middlewares call
	 *
	 **/
	'middleware_path' => '\\App\\Controllers\\Middleware\\',
	
	/**
	 * @Triggers namespace for database triggers call
	 *
	 **/
	'triggers_path' => '\\App\\Triggers\\'
];
