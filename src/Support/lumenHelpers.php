<?php

if (!function_exists('config_path')) {
	function config_path($path = '')
	{
		return app()->basePath() . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
	}
}

if (!function_exists('public_path')) {
	function public_path($path = '')
	{
		return env('PUBLIC_PATH', base_path('public')) . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
	}
}

if (!function_exists('asset')) {
	function asset($path, $secure = null)
	{
		return app('url')->asset($path, $secure);
	}
}

if (!function_exists('mix')) {
	function mix($file, $directory = '')
	{
		if (!empty($directory)) {
			$file = "{$directory}/$file";
		}
		$unversioned = public_path($file);
		if (file_exists($unversioned)) {
			return '/' . trim($file, '/');
		}

		throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
	}
}