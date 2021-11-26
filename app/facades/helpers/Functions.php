<?php

if (! function_exists('css_path')) {
	function css_path(?string $path = null): string {
		return __DIR__ . '/../../../public/css/' . ltrim($path, '/');
	}
}

if (! function_exists('js_path')) {
	function js_path(?string $path = null): string {
		return __DIR__ . '/../../../public/js/' . ltrim($path, '/');
	}
}

if (! function_exists('view_path')) {
	function view_path(?string $path = null): string {
		return __DIR__ . '/../../../app/views/' . ltrim($path, '/');
	}
}

if (! function_exists('app_path')) {
	function app_path(?string $path = null): string {
		return __DIR__ . '/../../../' . ltrim($path, '/');
	}
}

if (! function_exists('storage_path')) {
	function storage_path(?string $path = null): string {
		return __DIR__ . '/../../../storage/' . ltrim($path, '/');
	}
}

if (! function_exists('assets_path')) {
	function assets_path(?string $path = null): string {
		return __DIR__ . '/../../../public/assets/' . ltrim($path, '/');
	}
}

if (! function_exists('vendor_path')) {
	function vendor_path(?string $path = null): string {
		return __DIR__ . '/../../../vendor/' . ltrim($path, '/');
	}
}

if (! function_exists('path')) {
	function path(?string $path = null): string {
		return __DIR__ . '/../../../' . ltrim($path, '/');
	}
}

if (! function_exists('pd')) {
	function pd($item, $die = true): void {
		echo '<pre>';
		print_r($item);
		echo '</pre>';
		
		if ($die) {
			die();
		}
	}
}
