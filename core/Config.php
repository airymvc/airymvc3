<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This class retrieves all the config.ini values and save in an object.
 *
 * @package framework\core\Config
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * 
 * @see framework/config/config.ini
 * @see framework/config/example.config.ini
 */
class Config{
    
    private static $instance;
    private $_iniFilePath;
    
    const SINGLE_DB_SETTING_DB_ID = 'db1';
    const JS_INI_KEY  = 'jsconfig';
    const LOG_INI_KEY  = 'logconfig';
    const CS_INI_SYS_KEY = 'cssconfig';
    const DB_INI_SYS_KEY = 'dbconfig';

    const JSKEY       = 'script';
    const CSSKEY      = 'css';
    
    function __construct() 
    {
        $root = PathService::getRootDir();
        //Read the project's config first
		//This is the project's config
        $this->_iniFilePath = $root . DIRECTORY_SEPARATOR . 'project' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
        //Fallback config path to framework's level config folder's config.ini
        //This is the framework's config
        $frameworkConfig = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini';
        if (!file_exists($this->_iniFilePath)) {
        	if (file_exists($frameworkConfig)) {
            	$this->_iniFilePath = $frameworkConfig;
        	} else {
        		throw new AiryException("No config file in {$frameworkConfig} error!!");
        	}   
        }
        
        
    }
    
    /**
     *  Use Singleton pattern here
     *  
     *  @return object
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }

    /**
     *  Set config.ini file path.
     *  
     *  @param string $path the path of config.ini file.
     */
    public function setIniFilePath($path) {
    	$this->_iniFilePath = $path;
    }
    
    /**
     * The result depends on multiple databases
     * [0] => array of database #1 setting
     * [1] => array of database #2 setting
     * 
     * @return array database config values
     */
    public function getDBConfig()
    {
         $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $dbArray = $iniArray['DB'];

         $result   = array();
         $parseIni = $this->convertMultiIni($dbArray);
         //Single database setting
         //Just one layer key-value structure
         if (!isset($parseIni[self::DB_INI_SYS_KEY])) {
         	 $result[0] = $dbArray;
         	 //for single database setting, we use db1 for key too
         	 $result[self::SINGLE_DB_SETTING_DB_ID] = $dbArray;
         	 return $result;
         }
         
         //For multiple database setting
         //Multiple layer structure
         foreach ($parseIni[self::DB_INI_SYS_KEY] as $mkey => $kv) {
             $tmpArray = array();
             foreach ($kv as $key => $value) {
                  $tmpArray[$key] = $value;
             }
             $result[] = $tmpArray;
             $result[$mkey] = $tmpArray;
         }

         return $result;
     }
     
//     public function getTimezone()
//     {
//     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
//         if (!isset($iniArray['Time_Zone']) || !isset($iniArray['Time_Zone']['timezone'])) {
//             return null;
//         }       
//         $tzArray = $iniArray['Time_Zone'];
//         
//         return $tzArray['timezone'];
//     }

     
     /**
      * The authentication value in the config.ini. Two values - enable | disable.
      *
      * @return string authentication config value
      */     
     public function getAuthenticationConfig()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
     	 if (!isset($iniArray['Authentication'])) {
     	 	 return null;
     	 }
     	 if (strtolower($iniArray['Authentication']['use_authentication']) == "true" ||
     	     strtolower($iniArray['Authentication']['use_authentication']) == "on") {
     	     strtolower($iniArray['Authentication']['use_authentication']) == "enable";	
     	 }
         if (strtolower($iniArray['Authentication']['use_authentication']) == "false" ||
     	     strtolower($iniArray['Authentication']['use_authentication']) == "off") {
     	     strtolower($iniArray['Authentication']['use_authentication']) == "disable";	
     	 }     	 
         return  $iniArray['Authentication'];
     }
    
     /**
      * Module, controller and action keywords: 
      * These keywords will be used in URL as the query string variables.
      * @example ?md=moduleName&cl=controllerName&at=actionName
      *
      * @return array an array of module, controller, and action values
      */     
     public function getMVCKeyword()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         if(!isset($iniArray['MVC_Keyword']))
         {
            return null;
         }
         $mvcArray = $iniArray['MVC_Keyword'];
         
         return $mvcArray;
     }
     
     /**
      * Get the default module value
      * 
      * @return string the default module value
      */
     public function getDefaultModule()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $modules = $iniArray['Module'];

         $defaultModule = $modules["default"];

         return $defaultModule;
     }
     
     /**
      * Get all the language config values.
      *
      * @return array the language values
      */
     public function getLanguage()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         $language = $iniArray['Language'];

         return $language;
     }
     
     /**
      * Get the leading file config value.
      *
      * @return string the leading file value
      */
     public function getLeadFile()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($iniArray['Lead_File'])) {
             return array('filename' => 'index.php');
         }

         return $iniArray['Lead_File'];
     }
     
     /**
      * Get the module keyword config value.
      *
      * @return string module keyword value
      */
     public function getModuleKeyword()
     {
         $mvcArray = $this->getMVCKeyword();
         if (isset($mvcArray['module'])&& !is_null($mvcArray)) {
             return $mvcArray['module'];
         }
         
         return 'module';
     }
     
     /**
      * Get the controller keyword config value.
      *
      * @return string controller keyword value
      */
     public function getControllerKeyword()
     {
         $mvcArray = $this->getMVCKeyword();
         if (isset($mvcArray['controller']) && !is_null($mvcArray)) {
             return $mvcArray['controller'];
         }
         return 'controller';       
     }
     
     /**
      * Get the action keyword config value.
      *
      * @return string action keyword value
      */
     public function getActionKeyword()
     {
         $mvcArray = $this->getMVCKeyword();
         if (isset($mvcArray['action'])&& !is_null($mvcArray)) {
             return $mvcArray['action'];
         }
         return 'action';        
     }
     
     /**
      * Get the default language value.
      *
      * @return string the default language value
      */
     public function getDefaultLanguage()
     {
         $langArray = $this->getLanguage();
         if (isset($langArray['default'])) {
             return $langArray['default'];
         }
         return 'en-US';        
     }
     
     /**
      * Get the language folder relative to the application folder.
      * The default value = "lang".
      *
      * @return string the language folder
      */
     public function getLanguageFolder()
     {
         $langArray = $this->getLanguage();
         if (isset($langArray['folder']) && !empty($langArray['folder'])) {
             return $langArray['folder'];
         }
         return 'lang';        
     }

     /**
      * Get the language keyword. The default value = "lang".
      *
      * @return string the language keyword value.
      */
     public function getLanguageKeyword()
     {
         $lang_array = $this->getLanguage();
         if (isset($lang_array['keyword']) && !empty($lang_array['keyword'])) {
             return $lang_array['keyword'];
         }
         return 'lang';        
     }
     
     /**
      * Get the leading file name language keyword. The default value = "index.php".
      *
      * @return string the leading file name.
      */
     public function getLeadFileName()
     {
         $lead_file_array = $this->getLeadFile();
         if (isset($lead_file_array['filename']) && !empty($lead_file_array['filename'])) {
             return $lead_file_array['filename'];
         }
         return 'index.php';        
     }
     
     /**
      * This method gets an array of plugin libraries.
      * 
      * @example array('script' => zero base array, 'css' => zero base array);
      * @return array script plugin config values.
      */
     public function getScriptPlugin()
     {
     	 $ini_array = parse_ini_file ($this->_iniFilePath, true);
         if (!isset($ini_array['JS_Plugin'])) {
             return null;
         }
         $result = array();
         foreach ($ini_array['JS_Plugin'] as $key => $value) {
              $configs = explode('.', $key);
              if ($configs[0] == self::CS_INI_SYS_KEY) {
                  $result[self::CSSKEY][] = $value;
              }
              if ($configs[0] == self::JS_INI_KEY) {
                  $result[self::JSKEY][] = $value;
              }                      
         }
    
         return $result;         
     }
     
     /**
      * Get the error setting value.
      *
      * @return string the error setting value.
      */
     public function getErrorSetting() {
         $iniArray = parse_ini_file ($this->_iniFilePath, true);
     	 if (!isset($iniArray['Error'])) {
     	 	 return null;
     	 }     	
     	 return $iniArray['Error'];
     }
     
     /**
      * Get the config value of display error. Get "enable" | "disable".
      *
      * @return string the display error setting value.
      */
     public function getDisplayError()
     {
         $errorArray = $this->getErrorSetting();
         if (!isset($errorArray['display_error'])) {
         	return null;
         }
     	 
     	 if (strtolower($errorArray['display_error']) == "true" ||
     	     strtolower($errorArray['display_error']) == "on") {
     	     strtolower($errorArray['display_error']) == "enable";	
     	 }
         if (strtolower($errorArray['display_error']) == "false" ||
     	     strtolower($errorArray['display_error']) == "off") {
     	     strtolower($errorArray['display_error']) == "disable";	
     	 }     	 
         return  $errorArray['display_error'];
     }
     
//     public function getErrorForwarding()
//     {
//         $errorArray = $this->getErrorSetting();
//         if (!isset($errorArray['error_forwarding'])) {
//         	return null;
//         }
//     	 
//     	 if (strtolower($errorArray['error_forwarding']) == "true" ||
//     	     strtolower($errorArray['error_forwarding']) == "on") {
//     	     strtolower($errorArray['error_forwarding']) == "enable";	
//     	 }
//         if (strtolower($errorArray['error_forwarding']) == "false" ||
//     	     strtolower($errorArray['error_forwarding']) == "off") {
//     	     strtolower($errorArray['error_forwarding']) == "disable";	
//     	 }     	 
//         return  $errorArray['error_forwarding'];
//     }
     
     
     /**
      * Construct the array of ini array.
      * 
      * @param array $keyValues key-value pairs
      * @return array key-value pairs
      */
     private function convertMultiIni ($keyValues) {
        
        $result = array();

        foreach($keyValues as $key => $value)
        {
            $tmp = &$result;
            foreach(explode('.', $key) as $k) {
                $tmp = &$tmp[$k];
            }
            $tmp = $value;
        }
        unset($tmp);

        return $result; 
     }
     
     /**
      * Get the cache array.
      *
      * @return array cache config values
      */
     public function getCacheConfig()
     {
     	 $iniArray = parse_ini_file ($this->_iniFilePath, true);
     	 $cache = array();
         if (isset($iniArray['Cache'])) {
     	 	$cache = $iniArray['Cache'];
         }

         return $cache;
     }
     
     /**
      * Get the cache file saving folder that is relative to application folder.
      *
      * @return string cache path
      */
     public function getCacheFolder()
     {
         $cacheArray = $this->getCacheConfig();
         if (isset($cacheArray['folder']) && !empty($cacheArray['folder'])) {
             return $cacheArray['folder'];
         }
         return 'data'.DIRECTORY_SEPARATOR.'cache';        
     }
     
     /**
      * Get the log file saving folder that is relative to application folder.
      *
      * @return string cache path
      */     
     public function getLogFolders()
     {
     	$ini_array = parse_ini_file ($this->_iniFilePath, true);
     	if (!isset($ini_array['Log'])) {
     		return null;
     	}
     	$result = array();
     	foreach ($ini_array['Log'] as $key => $value) {
     		$configs = explode('.', $key);
     		if ($configs[0] == self::LOG_INI_KEY) {
     			$result[] = $value;
     		}
     	}
     	if (empty($result)) {
     		$result[0] = "data" . DIRECTORY_SEPARATOR ."log";
     	}
     
     	return $result;
     }
}
?>
