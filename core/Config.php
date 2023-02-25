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
 * This class retrieves all the config.{envirionment}.json values and save in an object.
 *
 * @package airymvc\core\Config
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * 
 * @see airymvc/config/config.{envirionment}.json
 */
class Config{
	
	protected $configArray = NULL;
	
    public function __construct($configArray) {
		$this->configArray = $configArray;
    }

    
    /**
     * The result depends on multiple databases
     * [0] => array of database #1 setting
     * [1] => array of database #2 setting
     * 
     * @param int $index index of an array
     * @return array database config values || array of a database config settings
     */
    public function db($index = NULL) {
     	if (!isset($this->configArray["%db"])) {
     		return NULL;
     	}
     	if (is_null($index)) {
     		return $this->configArray["%db"];
     	}
     	return $this->configArray["%db"][$index];
     }
     
     
     public function dbMode() {
     	if (!isset($this->configArray["%dbMode"])) {
     		return NULL;
     	}
     	return $this->configArray["%dbMode"];
     }

     /**
      * The result is multiple templates
      * [0] => array of template #1 setting
      * [1] => array of template #2 setting
      *
      * @param int $index index of an array
      * @return array templates || array of a template settings
      */
     public function templates($index = NULL) {
     	if (!isset($this->configArray["%templates"])) {
     		return NULL;
     	}
     	if (is_null($index)) {
     		return $this->configArray["%templates"];
     	}
     	return $this->configArray["%templates"][$index]; 
     }

     /**
      * Display application error or not.
      * 
      * @return boolean
      */
     public function displayError() {
     	if (!isset($this->configArray["%display_error"])) {
     		return false;
     	}
     	return $this->configArray["%display_error"];     	
     }
   
}
?>
