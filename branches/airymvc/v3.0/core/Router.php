<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This class handles the language codes
 *
 * @package framework\core\Router
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Router {

    // Valid constant names
    const CONTROLLER_POSTFIX = 'Controller';
    const ACTION_POSTFIX = 'Action';
    const MODEL_POSTFIX = 'Model';
    const VIEW_POSTFIX = 'View';
    const DEFAULT_PREFIX = 'index';
    const ALLOW_THIS_ACTION = 'inlayout_mca';
    const LANGUAGE_CODE = 'language_code';

    private $controller;
    private $action;
    private $moduleName;
    private $params;
    private $controllerName;
    private $actionName;
    private $key_val_pairs;
    private $qstring_keys;
    private $languageCode;
    private $root;


    function __construct($moduleName = NULL, $controllerName = NULL, $actionName = NULL, $params = NULL) {
    	if (is_null($moduleName) && 
    		is_null($controllerName) && 
    		is_null($actionName) && 
    		is_null($params) ) {
            $this->prepare();
    	} else {
    		$this->setAll($moduleName, $controllerName, $actionName, $params);
    	}
    }
    /**
     * This method gets all the language codes.
     *
     * @param string $moduleName The module name.
     * @param string $controllerName The controller name.
     * @param string $actionName The action name.
     * @param array  $params The parameters that passed via http call.
     * @return array All the language codes.
     */
    private function setAll($moduleName, $controllerName, $actionName, $params) {
    	// setup module first
        $this->moduleName = RouterHelper::hyphenToCamelCase($moduleName); //module name
        $this->setModule($this->moduleName);

        //Set Controller Name; also set the default model and view here
        $this->controllerName = RouterHelper::hyphenToCamelCase($controllerName, TRUE);
        $this->setDefaultModelView($this->controllerName);
        MvcReg::setControllerName($this->controllerName); 
        $this->controller = RouterHelper::hyphenToCamelCase($controllerName, TRUE).self::CONTROLLER_POSTFIX;//controller name

        
        //Setting action 
        $this->actionName = RouterHelper::hyphenToCamelCase($actionName);
        MvcReg::setActionName($this->actionName);
        $this->action = RouterHelper::hyphenToCamelCase($actionName).self::ACTION_POSTFIX; //action name
        
        $this->setDefaultActionView($this->controllerName, $this->actionName);
        $this->setModuleControllerAction($this->moduleName, $this->controllerName, $this->actionName);
        
        $this->params = $params;
        Parameter::unsetAllParams();
        Parameter::setParams($this->params);  
    }
    
    /**
     * The method is to prepare all the values for routing.
     * 
     * When using url redirect, the following is an example.
     * 
     * @example "See the Apache config file" The following is the rewrite rules in config file.
     * <VirtualHost *:80>
     * 		AliasMatch ^/(js|img|css)/(.*)$ /usr/local/zend/apache2/htdocs/zframework/webroot/$1/$2
     * </VirtualHost>
     * <Directory "/usr/local/zend/apache2/htdocs/zframework">
     * 		RewriteEngine on
     * 		RewriteBase /
     * 		RewriteCond %{REQUEST_URI} !\.(css|png|jpe?g|gif)$ [NC]
     * 		RewriteCond %{REQUEST_URI} !index\.php [NC]
     * 		RewriteRule  ^([^/]+)/([^/]+)/([^/]+)(.*)$ /index.php?md=$1&cl=$2&at=$3$4 [QSA]
     * </Directory>
     * The value for key at (action) will attach the key-value path. We need to resolve it.
     * 
     * @throws AiryException
     */
    private function prepare() {
    	$this->root = PathService::getRootDir();        
        $config = Config::getInstance();
        $mvc_array = $config->getMVCKeyword();
        $moduleKeyword = array_key_exists('module', $mvc_array) ? $mvc_array['module']: "module";
        $controllerKeyword = array_key_exists('controller', $mvc_array)? $mvc_array['controller']: "controller";
        $actionKeyword = array_key_exists('action', $mvc_array)? $mvc_array['action']: "action";;
        $languageKeyword = $config->getLanguageKeyword();
        $defaultLanguageCode = $config->getDefaultLanguage();

        //Before we jump into the getting params process, we need re-map the $_GET and $_POST due to the apache URL rewrite setting
        //We use RouterUrlRewritter to deal with that
        $urlRewritter = new RouterUrlRewriter();
        $urlRewritter->remapGetAndPost($actionKeyword);
        
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $keys = array_keys($_GET); //get URL after '?'
        }else {
            $qstringPieces = explode('&', $_SERVER['QUERY_STRING']);

            foreach ($qstringPieces as $key =>$value) {
                $x = explode('=', $value);
                $value = isset($x[1]) ? $x[1] : "";
                $this->key_val_pairs[$x[0]] = $value;
                $this->qstring_keys[$x[0]];
            }
            $keys = array_keys($_POST); //get form variables
        }
        
        foreach ($keys as $key => $value) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                //make to lower case
                $this->key_val_pairs[strtolower($value)] = $_GET[$value];
            } else {
                //make to lower case
                $this->key_val_pairs[strtolower($value)] = $_POST[$value];
            }
        }
        
        if ($moduleKeyword == $controllerKeyword ||
            $actionKeyword == $controllerKeyword ||
            $moduleKeyword == $actionKeyword) {
            throw new AiryException("Duplicate MVC Keywords. Module's, Controller's and Action's keywords should be unique.");
        }

        // setup module first
        if  (!empty($this->key_val_pairs[$moduleKeyword])) {
            $this->moduleName = RouterHelper::hyphenToCamelCase($this->key_val_pairs[$moduleKeyword]); //module name
            $this->setModule($this->moduleName);
            unset($this->key_val_pairs[$moduleKeyword]);
        }else {
            $this->moduleName = $config->getDefaultModule(); //no module name means "default" module
            $this->setModule($this->moduleName);            
        }

        //Set Controller Name; also set the default model and view here
        //Controller's first letter is upper case
        if (!empty($this->key_val_pairs[$controllerKeyword])) {
            $this->controllerName = RouterHelper::hyphenToCamelCase($this->key_val_pairs[$controllerKeyword], TRUE);
            $this->setDefaultModelView($this->controllerName);
            MvcReg::setControllerName($this->controllerName); 
            
            $this->controller = $this->controllerName.self::CONTROLLER_POSTFIX;//controller name
            unset($this->key_val_pairs[$controllerKeyword]);

        }else {
            $this->controllerName = ucfirst(self::DEFAULT_PREFIX);
            $this->controller = $this->controllerName.self::CONTROLLER_POSTFIX;
            $this->setDefaultModelView(self::DEFAULT_PREFIX);
            MvcReg::setControllerName($this->controllerName);
        }
        
        //Setting action 
        if  (!empty($this->key_val_pairs[$actionKeyword])) {
            $this->actionName = RouterHelper::hyphenToCamelCase($this->key_val_pairs[$actionKeyword]);
            MvcReg::setActionName($this->actionName);
            
            $this->action = RouterHelper::hyphenToCamelCase($this->key_val_pairs[$actionKeyword]).self::ACTION_POSTFIX; //action name
            unset($this->key_val_pairs[$actionKeyword]);
        }else {
            $this->actionName = self::DEFAULT_PREFIX;
            MvcReg::setActionName($this->actionName);
            $this->action = self::DEFAULT_PREFIX.self::ACTION_POSTFIX;
        }
        
        $this->setDefaultActionView($this->controllerName, $this->actionName);
        $this->setModuleControllerAction($this->moduleName, $this->controllerName, $this->actionName);
        
        //Setting language code and Session
        if  (!empty($this->key_val_pairs[$languageKeyword])) {
            $this->languageCode = $this->key_val_pairs[$languageKeyword];
            $this->setLanguageCode($this->languageCode);
            
            //set langauge session based on module
            //@TODO: need to consider to add a project layer in the futuure
            $_SESSION[$this->moduleName][self::LANGUAGE_CODE] = $this->languageCode;
            unset($this->key_val_pairs[$languageKeyword]);
        } else {
        	if (!empty($_SESSION[$this->moduleName][self::LANGUAGE_CODE])) {
        		$this->setLanguageCode($_SESSION[$this->moduleName][self::LANGUAGE_CODE]);
        	} else {
            	$this->setLanguageCode($defaultLanguageCode);
            	//@TODO: need to consider to add a project layer in the futuure
            	$_SESSION[$this->moduleName][self::LANGUAGE_CODE] = $defaultLanguageCode;
        	}
        }
        
        //Getting serialize data for setting authentication allowing actions
        if  (!empty($this->key_val_pairs[self::ALLOW_THIS_ACTION])) {
             if (isset($this->key_val_pairs[self::ALLOW_THIS_ACTION])) {
             	 $filename = $this->key_val_pairs[self::ALLOW_THIS_ACTION];
             	 $checkContent = $this->moduleName .";" .$this->controllerName .";".$this->actionName;
             	 $checkContent = md5($checkContent);
             	 if (trim(FileCache::getFile($filename)) == trim($checkContent)) {
            	 	 Authentication::addLayoutAllowAction($this->moduleName, $this->controllerName,  $this->actionName);
            	 	 FileCache::removeFile($filename);
             	 }
             }
             unset($this->key_val_pairs[self::ALLOW_THIS_ACTION]);
        }        
        
        $this->params = $this->key_val_pairs;
        Parameter::unsetAllParams();
        Parameter::setParams($this->params);    
    }

    
    public function getModuleName() {
        return $this->moduleName;
    }
    public function getAction() {
        return $this->action;
    }
    public function getController() {
        return $this->controller;
    }
    public function getParams() {
        return $this->params;
    }

    /**
     * Get the controller name.
     * @return string The $controllerName.
     */
    public function getControllerName() {
        return $this->controllerName;
    }

    /**
     * Get the action name.
     * @return string The $actionName.
     */
    public function getActionName() {
        return $this->actionName;
    }
    
    /**
     * Set the parameters.
     * @param array $params The parameters passed by http.
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * Set the controller name.
     * @param string $controllerName
     */
    public function setControllerName($controllerName) {
        $this->controllerName = $controllerName;  
    }
    /**
     * Set the default model view.
     * @deprecated Not recommended.
     * @param string $controllerName
     */
    public function setDefaultModelView($controllerName)
    {
        $modelClassName = $controllerName . self::MODEL_POSTFIX;
		$viewClassName = $controllerName . self::VIEW_POSTFIX;
        $modelFile = "project". DIRECTORY_SEPARATOR. "modules" .DIRECTORY_SEPARATOR. $this->moduleName .DIRECTORY_SEPARATOR."models" .DIRECTORY_SEPARATOR. $modelClassName.".php";
        $viewFile = "project". DIRECTORY_SEPARATOR. "modules".DIRECTORY_SEPARATOR.$this->moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $viewClassName .".php";

        MvcReg::setModelClassName($modelClassName);
        MvcReg::setViewClassName($viewClassName);
        MvcReg::setModelFile($modelFile);
        MvcReg::setViewFile($viewFile);  
    }
    /**
     * Set the default action view.
     * @param string $controllerName
     * @param string $actionName
     */    
    public function setDefaultActionView($controllerName, $actionName)
    {
    	$actionViewArray = RouterHelper::getActionViewData($this->moduleName, $controllerName, $actionName);
        $actionViewClassName = $actionViewArray[0];
        $actionViewFile = $actionViewArray[1];
        
        MvcReg::setActionViewClassName($actionViewClassName);
        MvcReg::setActionViewFile($actionViewFile);  
    }
    /**
     * Remove the default action view.
     */    
    public function removeDefaultActionView(){
        MvcReg::setActionViewClassName(null);
        MvcReg::setActionViewFile(null);        
    }
    /**
     * Set the module name.
     * @param string $moduleName
     */    
    public function setModule($moduleName) {
        MvcReg::setModuleName($moduleName);
    }
    /**
     * Set the module.
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     */
    public function setModuleControllerAction($moduleName, $controllerName, $actionName) {
        MvcReg::setModuleName($moduleName);
        MvcReg::setControllerName($controllerName);
        MvcReg::setActionName($actionName);
    }
    /**
     * Set the language code.
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode) {
        LangReg::setLanguageCode($languageCode);
    }
    
}
?>
