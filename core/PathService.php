<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This utility class saves objects that initially are used
 *
 * @package framework\core\PathService
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class PathService {
	
	/**
	 * The instance of the class itself
	 *
	 * @var object $instance the instance
	 */
    private static $instance;
    
    /**
     * Used for singleton pattern
     * 
     * @return object the instance of the class
     */    
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }
    
    /**
     * Get the absolute host URL
     * 
     * @return string
     */
    public static function getAbsoluteHostURL() {
    	$instance = self::getInstance();
    	return $instance->getAbsoluteHostURLData();
    }

    /**
     * Get the absolute host path
     *
     * @return string
     */
    public static function getAbsoluteHostPath() {
    	$instance = self::getInstance();
    	return $instance->getAbsoluteHostPathData();
    }    
    
    /**
     * Compose the URL based on the module, controller, action, parameters and directive
     *
     * @param string $moduleName The module name.
     * @param string $controllerName The controller name.
     * @param string $actionName The action name.
     * @param array $params URL parameters.
     * @param boolean $isDirective Determine if the URL is in the directive format, not the query string format.
     * 
     * @return string  The URL value that is composed from the passed variables. 
     */
    public static function getFormActionURL($moduleName, $controllerName, $actionName, $params = null, $isDirective = False) {
    	$instance = self::getInstance();
    	return $instance->getFormActionURLData($moduleName, $controllerName, $actionName, $params, $isDirective);
    }    

    /**
     * Get the application (root) directory
     *
     * @return string
     */
    public static function getRootDir() {
    	$instance = self::getInstance();
    	return $instance->getRootDirData();
    }     
    
    /**
     * Get the project directory
     *
     * @return string
     */
    public static function getProjectDir() {
    	$instance = self::getInstance();
    	return $instance->getProjectDirData();
    }

    /**
     * Get the module directory
     *
     * @return string
     */
    public static function getModulesDir() {
    	$instance = self::getInstance();
    	return $instance->getModulesDirData();
    }    
    

    /**
     * Get the absolute host URL value
     *
     * @return string the http URL
     */
    private function getAbsoluteHostURLData() {
          $url = 'http://' . $this->getAbsoluteHostPath();
          return $url;
    }
    
    /**
     * Get the absolute host path
     *
     * @return string the host path
     */    
    private function getAbsoluteHostPathData() {
          $serverName = $_SERVER['SERVER_NAME'];
          $serverPort = $_SERVER['SERVER_PORT'];
          $indexRelativePath = $_SERVER['SCRIPT_NAME'];
          $config = Config::getInstance();
          $leadFileName = $config->getLeadFileName();
          $replace = "/" . $leadFileName;
          $vfolder = str_replace($replace, '', $indexRelativePath);
          $serverHost = $serverName. ":" . $serverPort . $vfolder;
          return $serverHost;
    }
    
    /**
     * Compose the URL based on the module, controller, action, parameters and directive
     *
     * @param  string  $moduleName The module name
     * @param  string  $controllerName the controller name
     * @param  string  $actionName the action name
     * @param  array   $params URL parameters
     * @param  boolean $isDirective determine if the URL is in the directive format, not the query string format
     * @return string  the URL value that is composed from the passed variables
     */
    private function getFormActionURLData($moduleName, $controllerName, $actionName, $params = null, $isDirective = False) {
                $config = Config::getInstance();
                $mkey = $config->getModuleKeyword();
                $ckey = $config->getControllerKeyword();
                $akey = $config->getActionKeyword();
                $leadFileName = $config->getLeadFileName();
                
                $queryOp = "?";
                if ($isDirective) {
                	$url = $this->getAbsoluteHostURL()."/{$moduleName}/{$controllerName}/{$actionName}";
                } else {
                	$queryOp = "&";
                	$url = $this->getAbsoluteHostURL()."/{$leadFileName}?{$mkey}={$moduleName}&{$ckey}={$controllerName}&{$akey}={$actionName}";
                }
                
                if ($params == null){ 
                    return $url;
                }

                $queryStr = "";
                foreach ($params as $key => $value) {
                	if ($queryStr == "") {
                		$queryStr = "{$key}={$value}";
                	} else {
                		$queryStr .= "&{$key}={$value}";
                	}
                }    
                $url .= "{$queryOp}{$queryStr}";
                
                return $url;
    }  
     
    /**
     * Get the application (root) directory
     *
     * @return string
     */
    private function getRootDirData() {
        $dir = dirname(dirname(__FILE__));
        return $dir;
    }
    
    /**
     * Get the project directory
     *
     * @return string
     */
    private function getProjectDirData() {
        $rootDir = $this->getRootDir();
        $projDir = $rootDir . DIRECTORY_SEPARATOR . "project";
        return $projDir;
    }
    
    /**
     * Get the module directory
     *
     * @return string
     */
    private function getModulesDirData() {
        $projDir = $this->getProjectDir();
        $modulesDir = $projDir . DIRECTORY_SEPARATOR . "modules";
        return $modulesDir;               
    }

}

?>