<?php
namespace airymvc;

define('__AIRYMVC_ROOT__', __DIR__);

class Airy {
	public static function start() {
		include dirname(__FILE__) . DIRECTORY_SEPARATOR ."Init.php";
	}
}