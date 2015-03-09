<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This utility class saves objects that initially are used.
 *
 * @package framework\core\Parameter
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Parameter {
	
    /**
     * @var array $_params This is used for save the varialbe into an array
     */
    public static $_params = array();
    
    /**
     * setter
     * @param array $params saving the URL parameters
     */
    public static function setParams($params) {
        
        self::$_params = $params;
    }
    
    /**
     * Getter of params
     * @return array url parameters
     */
    public static function getParams() {
        
        return self::$_params;
    }
    
    /**
     * Setter of the parameters.
     * 
     * @param string $key the key of the parameter
     * @param string $value the value of the parameter
     */
    public static function setParam($key, $value) {
        
        self::$_params[$key] = $value;
    }
    
    /**
     * getter
     * 
     * @param string $key
     * @return string 
     */
    public static function getParam($key) {
        
        return self::$_params[$key];
    }
    
    /**
     * Set params to Session
     * 
     * @param array $params the key-value pairs that are saved in the session
     */
    public static function setSession($params) {
    	foreach ($params as $key => $value) {
    		$_SESSION[$key] = $value;
    	}
    }

    /**
     * Unset params to Session
     */
    public static function unsetSession($params) {
    	foreach ($params as $key => $value) {
    		unset($_SESSION[$key]);
    	}
    }    
    
    /**
     * Get parameter from Session based on the key
     * 
     * @param string $key the key value 
     * @return object 
     */
    public static function getSession($key) {
    	return $_SESSION[$key];
    } 
    
    /**
     * Get value that is saved in the Session based on the module
     * 
     * @param string $key
     * @return object the value according to the key
     * 
     */
    public static function getModuleSession($key) {
    	$moduleName = MvcReg::getModuleName();
    	return $_SESSION[$moduleName][$key];
    }
    
    /**
     * Set params to Session based on the module
     */
    public static function setModuleSession($params) {
    	$moduleName = MvcReg::getModuleName();
    	foreach ($params as $key => $value) {
    		$_SESSION[$moduleName][$key] = $value;
    	}
    }
    
    /**
     * Unset params to Session based on the module
     */
    public static function unsetModuleSession($params) {
    	$moduleName = MvcReg::getModuleName();
    	foreach ($params as $key => $value) {
    		unset($_SESSION[$moduleName][$key]);
    	}
    }

    /**
     * Unset all params from Session
     */
    public static function unsetAllParams() {
    	if (is_array(self::$_params)) {
    		foreach (self::$_params as $key => $value) {
    			unset(self::$_params[$key]);
    		}
    	}
    }
}