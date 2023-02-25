<?php

use airymvc\core\Mvc;

function __autoload($classname) {
	$namespace = substr($classname, 0, strrpos($classname, '\\'));
	$namespace = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
	$nameParts = explode(DIRECTORY_SEPARATOR, $namespace);
	//Default path is for airymvc framework
	$classPath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . '.php';
	//Application path
	if ($nameParts[0] != "airymvc") {
		$pathParts = explode("/", Mvc::$currentApp->relativePath());
		if (empty($pathParts[0])) {
			array_shift($pathParts);
		}
		$attachPath = join(DIRECTORY_SEPARATOR, $pathParts);
		$classPath = Mvc::$currentApp->documentRoot() . DIRECTORY_SEPARATOR .$attachPath 
		           . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . '.php';
	}
	if(is_readable($classPath)) {
		require_once $classPath;
	}
}