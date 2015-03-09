<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This utility class registers all the module, controller, model, view, and action values. 
 * 
 * @package framework\core\MvcReg
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MvcReg{
	
	/**
	 * @var string $_modelClassName module class name
	 */
     static $_modelClassName;
     /**
      * @var string $_viewClassName view class name
      */
     static $_viewClassName;
     /**
      * @var string $_actionViewClassName action view class name
      */
     static $_actionViewClassName;
     /**
      * @var string $_modelFile model file
      */
     static $_modelFile;
     /**
      * @var string $_viewFile view file
      */
     static $_viewFile;
     /**
      * @var string $_actionViewFile action view file
      */
     static $_actionViewFile;
     /**
      * @var string $_moduleName module name
      */
     static $_moduleName;
     /**
      * @var string $_controllerName controller name
      */
     static $_controllerName;
     /**
      * @var string $_actionName action name
      */
     static $_actionName;

     /**
      * Set the model class name
      * 
      * @param string $modelClassName
      */
     public static function setModelClassName($modelClassName)
     {
          self::$_modelClassName = $modelClassName;
     }
     
     /**
      * Get the model class name
      *
      * @param string
      */
     public static function getModelClassName()
     {
          return self::$_modelClassName;
     }

     /**
      * Set the view class name
      *
      * @param string $viewClassName
      */
     public static function setViewClassName($viewClassName)
     {
          self::$_viewClassName = $viewClassName;
     }
     
     /**
      * Get the view class name
      *
      * @return string 
      */
     public static function getViewClassName()
     {
          return self::$_viewClassName;
     }
     
     /**
      * Set the action view class name
      *
      * @param string $actionViewClassName
      */
     public static function setActionViewClassName($actionViewClassName)
     {
          self::$_actionViewClassName = $actionViewClassName;
     }
     
     /**
      * Get the action view class name
      *
      * @return string 
      */
     public static function getActionViewClassName()
     {
          return self::$_actionViewClassName;
     }
     
     /**
      * Set the module class name
      *
      * @param string $modelFile
      */
     public static function setModelFile($modelFile)
     {
          self::$_modelFile = $modelFile;
     }
     
     /**
      * Get the model file
      *
      * @return string model file
      */
     public static function getModelFile()
     {
          return self::$_modelFile;
     }

     /**
      * Set the view file
      *
      * @param string $viewFile The view file.
      */
     public static function setViewFile($viewFile)
     {
          self::$_viewFile = $viewFile;
     }
     
     /**
      * Get the view file
      *
      * @return string view file
      */
     public static function getViewFile()
     {
          return self::$_viewFile;
     }
     
     /**
      * Set the action view file
      *
      * @param string $actionViewFile
      */
     public static function setActionViewFile($actionViewFile)
     {
          self::$_actionViewFile = $actionViewFile;
     }
     
     /**
      * Get the action view file
      *
      * @return string action view file
      */
     public static function getActionViewFile()
     {
          return self::$_actionViewFile;
     }
     
     /**
      * Set the module name
      *
      * @param string $moduleName
      */
     public static function setModuleName($moduleName)
     {
          self::$_moduleName = $moduleName;
     }
     
     /**
      * Get the module name
      *
      * @return string module name
      */
     public static function getModuleName()
     {
          return self::$_moduleName;
     }     
   
     /**
      * Set the controller name
      *
      * @param string $controllerName
      */
     public static function setControllerName($controllerName)
     {
          self::$_controllerName = $controllerName;
     }
     
     /**
      * Get the controller name
      *
      * @return string controller name
      */
     public static function getControllerName()
     {
          return self::$_controllerName;
     }
     
     /**
      * Set the action name
      *
      * @param string $actionName
      */
     public static function setActionName($actionName)
     {
          self::$_actionName = $actionName;
     }
     
     /**
      * Get the action name
      *
      * @return string action name
      */
     public static function getActionName()
     {
          return self::$_actionName;
     }
     
}

?>
