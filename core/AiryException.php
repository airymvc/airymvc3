<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
 namespace airymvc\core;
 
/**
 * This handles the exception about the framework.
 *
 * @package framework\app\library\error\AiryException
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AiryException extends \Exception{

	public function __construct($message = '', $code = 0, Exception $previous = null) {
		$htmlMessage = "<b>Exception:</b><div>$message</div></br>";
		error_log($message);
		parent::__construct($htmlMessage, (int) $code, $previous);
	}

	
}