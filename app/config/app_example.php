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
	 * @mail configuration using in framework to send mails.
	 *  If array values are empty mail are not configured.
	 **/
	'mail' => [
		'smtp' => '',
		'user' => '',
		'password' => '',
		'port' => '',
		'from' => '',
		'fromName' => '',
	],
	
	/**
	 * @Security used for header Content-Security-Policy
	 **/
	'security' => [
		'enabled' => true,
		'protection' =>
			"default-src 'self'; style-src 'self' 'unsafe-inline' fonts.googleapis.com; font-src 'self' fonts.gstatic.com; img-src 'self' data:",
	
	],
	
	/**
	 * @Middlewares namespace for middlewares call
	 **/
	'middleware_path' => '\\App\\Controllers\\Middleware\\',
	
	/**
	 * @Triggers namespace for database triggers call
	 **/
	'triggers_path' => '\\App\\Triggers\\',
	
	/**
	 * @Model namespace for database model call
	 **/
	'model_path' => '\\App\\Models\\',
	
	'reporting_levels' => E_ERROR | E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_PARSE,
	
	'enable_api' => false,
	
	'error_listener' => false,
	
	'use_entity' => false,
	
	'no_photo_assets_img' => '/svg/no-photo.svg',
	
	'no_logged_exceptions' => []
];
