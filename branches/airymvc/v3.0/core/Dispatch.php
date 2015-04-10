<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\core;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Route.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Request.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Mvc.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'AiryException.php';

use airymvc\core\Route;
use airymvc\core\Request;
use airymvc\core\Mvc;
use airymvc\core\AiryException;
use airymvc\app\lib\view\TemplateMapper;

/**
 * This class handles all the dispatch rules and dispatch to the target action.
 *
 * @package framework\core\Dispatcher
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Dispatch{  

	private static $runController;
	
    /**
     * This method forward to the action.
     *
     * @param object $Router the instance of the Router class.
     */        
	public static function run() {     		
		$request = new Request();
		$request->set();
		$results = Route::resolveRoute($request);

		if (!is_null($results)) {
			Mvc::setCurrentMvc($results['mvc']);
			Mvc::setCurrentApp(Framework::app($results['mvc']->appName()));
			$request = $results['request'];
			TemplateMapper::templates();
			$tpl = TemplateMapper::getTemplate(Mvc::moduleName(), Mvc::controllerName(), Mvc::actionName());

	        session_start();
	        
	        //Take out global, not use
	        require_once Mvc::controllerFile();
	        $controller = Mvc::controllerClassName();
	        $action = Mvc::actionFunctionName();
	        
	        self::$runController = new $controller();
	        self::$runController->setParams($request->params());
	        self::$runController->setRequest($request);
	        self::$runController->setDefaultModel();
	        self::$runController->setDefaultView($tpl);
      
	        //init method acts as a constructor after all the variables being set
	        self::$runController->init();
	
	        if (method_exists(self::$runController, $action)) {
	        	self::$runController->$action();
	        } else {
				throw AiryException("AiryMVC: Missing Action Function.");
	        }
	        return self::$runController;
	
	        session_write_close(); 
		}
	}

}
?>