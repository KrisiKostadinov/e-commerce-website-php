<?php

class Autoloader
{
	private static $directories = [];

	public static function register(array $directories)
	{
		self::$directories = $directories;
		spl_autoload_register(array(__CLASS__, "autoload"));
	}
	
	public static function autoload($className)
	{
		$classFile = str_replace("\\", "/", $className) . ".php";
		if (file_exists($classFile)) {
			require_once $classFile;
			return;
		}
		
		foreach (self::$directories as $directory) {
			$classFile = $directory . "/" . str_replace("\\", "/", $className) . ".php";
			if (file_exists($classFile)) {
				require_once $classFile;
				return;
			}
		}
	}
}

Autoloader::register([
	"core",
	"database",
	"controllers/index",
	"controllers/auth",
	"controllers/products",
	"controllers/categories",
	"validators",
	"services",
	"utils",
]);
