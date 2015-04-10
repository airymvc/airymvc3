<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the framework log.
 *
 * @package framework\app\library\log\Logger
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Logger {
	
	protected $file;
	private static $instance;
	
	const LEVEL_INFO 	= "INFO";
	const LEVEL_WARNING = "WARNING";
	const LEVEL_ERROR 	= "ERROR";
	
    function __construct($file = null) {
		$this->file = $file;
    }
	
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($file = null);
        }    
        return self::$instance;
    } 
    /**
     * Set the log file.
     * @param string $file
     */    
    public function setFile($file) {
    	$instance = self::getInstance($file);
    	return $instance;    
    }
    /**
     * Write the logs.
     * @param string $message
     * @param string $level
     * @param string $file
     */
	public function write($message, $level = self::LEVEL_INFO, $file = null) {
		$saveFile = is_null($file) ? $this->file : $file;
		$log = sprintf("[%s] %s", $level, $message);
		file_put_contents($saveFile, $log, FILE_APPEND);
	}
	
}