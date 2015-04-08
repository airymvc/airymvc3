<?php


function __autoload($classname) {
	$namespace = substr($classname, 0, strrpos($classname, '\\'));
	$namespace = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
	$classPath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . '.php';
	if(is_readable($classPath)) {
		require_once $classPath;
	}
}