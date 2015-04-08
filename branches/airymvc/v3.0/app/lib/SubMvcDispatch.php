<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\app\lib;

use airymvc\core\Route;
use airymvc\core\Request;
use airymvc\core\Mvc;
use airymvc\core\AiryException;
use airymvc\app\lib\view\TemplateMapper;

/**
 * This class handles all the dispatch rules and dispatch to the target action.
 *
 * @package framework\core\SubMvcDispatch
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class SubMvcDispatch{  

    /**
     * This method forward to the action.
     *
     * @param object $Router the instance of the Router class.
     */        
	public static function run ($request, $subMvc) {

		TemplateMapper::templates();
		$tpl = TemplateMapper::getTemplate($subMvc->moduleName(), $subMvc->controllerName(), $subMvc->actionName());

        require_once Mvc::$currentApp->documentRoot() . "/" . $subMvc->controllerFile();
        $controller = $subMvc->controllerClassName();
        $action = $subMvc->actionFunctionName();
        
        $subController = new $controller();
        $subController->setParams($request->params());
        $subController->setRequest($request);
        $subController->setDefaultModel();
        $subController->setSubMvc($subMvc);
        $subController->setDefaultView($tpl, true);

        //init method acts as a constructor after all the variables being set
        $subController->init();

        if (method_exists($subController, $action)) {
         	ob_start();
        	$subController->$action();
         	$viewContent = ob_get_clean();
        	return $viewContent;
        } else {
			throw AiryException("AiryMVC: Missing Action Function.");
        }
		return NULL;
	}

}
?>