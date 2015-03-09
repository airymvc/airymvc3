<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This class handles all the dispatch rules and dispatch to the target action.
 *
 * @package framework\core\Dispatcher
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Dispatcher{  
	
    const ALL_CONTROLLERS = "ALL_CONTROLLERS";
    const ALL_ACTIONS = "ALL_ACTIONS";
    const CONTROLLER_POSTFIX = 'Controller';
    const ACTION_POSTFIX = 'Action';
    const MODEL_POSTFIX = 'Model';
    const VIEW_POSTFIX = 'View';
    
    private static $theController;

    /**
     * This method forward to the action.
     *
     * @param object $router the instance of the Router class.
     */
    public static function dispatchMVC($router) {
    	$moduleName = $router->getModuleName();
    	$params = $router->getParams();
    	$controllerName = $router->getControllerName();   //the name without 'Controller'
    	$actionName = $router->getActionName();
    	Dispatcher::forward($moduleName, $controllerName, $actionName, $params, $router);
    }
    
    /**
     * This method forward to the action.
     *
     * @param object $Router the instance of the Router class.
     */        
	public static function dispatch($Router) {     		
		$moduleName = $Router->getModuleName();  
		$params = $Router->getParams(); 
		$controllerName = $Router->getControllerName();   //the name without 'Controller'
        $actionName = $Router->getActionName();

        session_start();
        Dispatcher::forward($moduleName, $controllerName, $actionName, $params); 
        session_write_close(); 
	}

	/**
	 * The forward function is to call the action according to the module, controller and action.
	 * The function needs to consider all the forward restrictions and rules.
	 *
	 * @param string $moduleName the forwarding module name
	 * @param string $controllerName the forwarding controller name
	 * @param string $actionName the forwarding action name
	 * @param array  $params the url params
	 * @param object $router an instance of Router the default value = null
	 * 
	 */
	public static function forward($moduleName, $controllerName, $actionName, $params, $router = null)  
	{  
         $Router = is_null($router) ? new Router() : $router;
         $Router->setDefaultModelView($controllerName); 

         $controller = $controllerName.self::CONTROLLER_POSTFIX;
         $action     = $actionName.self::ACTION_POSTFIX;         

         $controllerfile = RouterHelper::getControllerFile($moduleName, $controller);

         try {	       
         	  if (file_exists($controllerfile)) {
                  require_once($controllerfile);
				
				  //Check special Authentication controller
				  /*
				   *  If status       
				   */
                  $Config = Config::getInstance();
                  $auth_array = $Config->getAuthenticationConfig();
				  if ($auth_array['use_authentication'] == "enable") {
                      /**
                       * if the controller and actions are those login related ones, 
                       * we exclude them, let them dispatch. 
                       */
                      if (Authentication::isLogin($moduleName)){
                          // need to acl rule after login
                          // put them here
                          //
                      	  if (Authentication::getSuccessController($moduleName) == $controllerName &&
                      		  Authentication::getSuccessAction($moduleName) == $actionName) {
                      	  	  Dispatcher::setRoute($moduleName, $controllerName, $actionName);
                      	  }
                          Dispatcher::toMVC($controller, $action, $params);  
                          return;
                      } else {      
                          //all allowed actions that are defined in acl.xml
                          $allows = Authentication::getAllAllows($moduleName);

                          //Change the controllerName to ControllerName
                          //because the router already transform the value
                          $controllerName = ucfirst($controllerName);
                          
                          //Dispatch sequence - checking allowing actions before checking login related actions
                          //(1) Check acl access exclusions
                          //Case #1: allow all controllers in the module
                          if ($allows == self::ALL_CONTROLLERS) {
                              Dispatcher::toMVC($controller, $action, $params); 
                              return;
                          }                         
                          
                          //Case #2: allow all actions in a specific controller
                          if (isset ($allows[$controllerName]) && ($allows[$controllerName] == self::ALL_ACTIONS)){ 
                              Dispatcher::toMVC($controller, $action, $params); 
                              return;
                          } 
                          
                          //Case #3: allow a specific action in a specific controller
                          if (isset($allows[$controllerName])) {
                              $allowActions = $allows[$controllerName];
                              foreach ($allowActions as $idx => $allowAction) {
                              	//echo "{$allowAction}=={$actionName}";
                                       if ($allowAction == $actionName) {
                                           Dispatcher::toMVC($controller, $action, $params);
                                           return;
                                       }
                              } 
                          } 
                          
                          //Case #4: Special cases, passing the actions in layout (due to using http request to get view)
                          if (isset(Authentication::$layoutAllows[$moduleName][$controllerName])) {
                              $allowActions = Authentication::$layoutAllows[$moduleName][$controllerName];
                              foreach ($allowActions as $idx=>$allowAction) {
                                       if ($allowAction == $actionName) {
                                       	   //unset the action
                                       	   Authentication::removeLayoutAllowAction($moduleName, $controllerName, $actionName);
                                       	   Dispatcher::toMVC($controller, $action, $params);
                                           return;
                                       }
                              } 
                          } 
                          
                          
                          //(2) Check login related actions
                          $loginActions = Authentication::getLoginExcludeActions($moduleName); 
                          if (isset($loginActions[$controllerName][$actionName])) {
                              Dispatcher::toMVC($controller, $action, $params); 
                              return;
                          }
                          
                          //(3) None of above satisfies, forward to login controller action
                          $loginControllerName = Authentication::getLoginController($moduleName);
                          $loginController     = Authentication::getLoginController($moduleName).self::CONTROLLER_POSTFIX;
                          $loginActionName     = Authentication::getLoginAction($moduleName);
                          $loginAction         = Authentication::getLoginAction($moduleName).self::ACTION_POSTFIX;
                          
                          Dispatcher::setRoute($moduleName, $loginControllerName, $loginActionName);
                          Dispatcher::toMVC($loginController, $loginAction, $params);
                       }
				} else {
                       Dispatcher::toMVC($controller, $action, $params);
				}     
		 	} else {
				$errorMsg = "Controller {$controller} or controller file {$controllerfile} is missing";
				throw new AiryException($errorMsg);		
		 	}  
		} catch (Exception $e) {
			$errorMsg = "<h3><b>Dispatching ERROR!</b></h3>" . $e->getMessage();
			$ifDisplayError = $Config = Config::getInstance()->getDisplayError();
			if ($ifDisplayError == "enable") {
				echo $errorMsg;
			}
		} 
	}

	/**
	 * The method sets or initializes module, controller, action, params, view variables and layout
	 *
	 * @param string  $controller the controller name that is initialized.
	 * @param string  $action the forwarding action name
	 * @param array   $viewVariables the view variables
	 * @param array   $params the url params
	 * @param boolean $inLayout if the view is in a layout
	 * 
	 * @return object the instance of the controller
	 * 
	 * @throws AiryException
	 */
	private static function toMVC($controller, $action, $params, $viewVariables = null, $inLayout = FALSE)  {
		//Take out global, not use
       	self::$theController = new $controller();   
       	//This means constructor does not initialize the necessary params
        self::$theController->initial($params, $viewVariables); 
        //init method acts as a constructor after all the variables being set
        self::$theController->init();
        if ($inLayout) {
        	$view = self::$theController->getView();
        	$view->setInLayout(true);
        	self::$theController->setView($view);
        }
        if (method_exists(self::$theController, $action)) {
			self::$theController->$action();
        } else {
        	$errorMsg = "The action {$action} in {$controller} is missing";
        	throw new AiryException($errorMsg);
        }    
        return self::$theController;
    }

    /**
     * The method sets default views and action
     *
     * @param string  $moduleName the module name
     * @param string  $controllerName the  action name
     * @param string  $actionName the action name 
     * 
     */
    private static function setRoute($moduleName, $controllerName, $actionName) {
    	$router = new Router();
    	$router->removeDefaultActionView();
    	$router->setDefaultActionView($controllerName, $actionName);
    	$router->setDefaultModelView($controllerName);
    	$router->setModuleControllerAction($moduleName, $controllerName, $actionName);    	
    }
}
?>