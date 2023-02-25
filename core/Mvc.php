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
 * This utility class registers all the module, controller, model, view, and action values. 
 * 
 * @package airymvc\core\Mvc
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Mvc{
	
	public static $currentMvc;
    
	public static $currentApp;
	
	public static $templateDir;
	
	public static $templateMapDir;
	

	/**
	 * Set the current requesting mvc
	 *
	 * @param MvcValue $mvc
	 */
	public static function setCurrentMvc($mvc)
	{
		self::$currentMvc = $mvc;
	}	
	
	/**
	 * Set the current requesting application/project
	 *
	 * @param MvcValue $mvc
	 */
	public static function setCurrentApp($app)
	{
		self::$currentApp = $app;
	}
	

	/**
	 * Get the model class name
	 *
	 * @param string
	 */
	public static function currentApp()
	{ 
		return self::$currentApp;
	}
	
	/**
	 * Get the model class name
	 *
	 * @param string
	 */
	public static function currentMvc()
	{
		return self::$currentMvc;
	}
     
	
	/**
	 * Get the controller file
	 *
	 * @param boolean $isAbsolute set if it is an absolute path or relative path
	 * @return string controller file
	 */
	public static function controllerFile($isAbsolute = TRUE)
	{
		if ($isAbsolute) {
			return self::$currentApp->documentRoot() . "/" . self::$currentMvc->controllerFile();
		}
		return self::$currentMvc->controllerFile();
	}

	
	/**
	 * Get the controller classname 
	 * 
	 * @return string controller classname
	 */
	public static function controllerClassName()
	{
		return self::$currentMvc->controllerClassName();
	}

	
	/**
	 * Get the action function name
	 *
	 * @return string action function name
	 */
	public static function actionFunctionName()
	{
		return self::$currentMvc->actionFunctionName();
	}
	
	
    /**
     * Get the model file
     *
     * @param boolean $isAbsolute set if it is an absolute path or relative path
     * @return string model file
     */
    public static function modelFile($isAbsolute = TRUE)
    {
     	if ($isAbsolute) {
     		return self::$currentApp->documentRoot() . "/" . self::$currentMvc->modelFile();
     	}
     	return self::$currentMvc->modelFile();
    }
    
    /**
     * Get the model classname
     *
     * @return string model classname
     */
    public static function modelClassName()
    {
    	return self::$currentMvc->modelClassName();
    }

    /**
     * Get the model file
     *
     * @param boolean $isAbsolute set if it is an absolute path or relative path
     * @return string view file
     */
    public static function viewFile($isAbsolute = TRUE)
    {
     	if ($isAbsolute) {
     		return self::$currentApp->documentRoot() . "/" . self::$currentMvc->viewFile();
     	}
     	return self::$currentMvc->viewFile();
    }


     
    /**
     * Get the module name
     *
     * @return string module name
     */
    public static function moduleName()
    {
        return self::$currentMvc->moduleName();
    }     
   

     
    /**
     * Get the controller name
     *
     * @return string controller name
     */
    public static function controllerName()
    {
          return self::$currentMvc->controllerName();
    }

     
    /**
     * Get the action name
     *
     * @return string action name
     */
    public static function actionName()
    {
          return self::$currentMvc->actionName();
    }
     
    /**
     * Set the folder that stores template files
     *
     * @param string $templateDir
     */
    public static function setTemplateDir($templateDir) {
    	self::$templateDir = $templateDir;
    }
    
    /**
     * Get the folder that stores template files
     *
     * @return string template folder
     */
    public static function templateDir() {
    	if (is_null(self::$templateDir)) {
    		$file = self::$currentApp->documentRoot() . DIRECTORY_SEPARATOR . 
    		        self::moduleName(). DIRECTORY_SEPARATOR . "templates";
    		self::setTemplateDir($file);
    	}
    	return self::$templateDir;
    }
    
    /**
     * Set the folder that stores template files
     *
     * @param string $templateDir
     */
    public static function setTemplateMapDir($templateMapDir) {
    	self::$templateMapDir = $templateMapDir;
    }
    
    /**
     * Get the folder that stores template files
     *
     * @return string template folder
     */
    public static function templateMapDir() {
    	if (is_null(self::$templateMapDir)) {
    		$file = self::$currentApp->documentRoot() . DIRECTORY_SEPARATOR . 
    		        self::moduleName(). DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "maps";
    		self::setTemplateMapDir($file);
    	}
    	return self::$templateMapDir;
    }
    
}


?>
