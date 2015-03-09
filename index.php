<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

	include(dirname(__FILE__) . DIRECTORY_SEPARATOR ."core".DIRECTORY_SEPARATOR."Ini.php");
	Initializer::initialize();
	$Router = Loader::load("Router");
	Dispatcher::dispatch($Router);
?>