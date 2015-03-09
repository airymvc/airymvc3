<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\app\library\view\Layout
 */
require dirname(dirname(__FILE__)) . "/view/Layout.php";

/**
 * This is the abstract class of the controller.
 *
 * @package framework\app\library\controller\AbstractController
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class AbstractController{
    
	
   		protected $params;
        
        protected $model;

        protected $layout;
        protected $view;
        
        
        //Other variables for a controller 
        protected $_viewDir;
        protected $_controllerDir;
        protected $_modelDir;

        
        const VIEW_POSTFIX = 'View';
        const ACTION_POSTFIX = 'Action';

        //The constructor function for the controller
        public function init() {}
        
        
        /**
         * Initialize the params and view variables
         * @param array $params The parameters in the POST or GET request.
         * @param array $viewVariables The view variables.
         */
        public function initial($params, $viewVariables = null) {
            $this->setDefaultModel();
            $this->view = new AppView();
            $this->setDefaultView();
            $this->setParams($params);
            $this->layout = new Layout();
            $this->layout->setView($this->view);
            $this->prepareVariables();
            
            //add view varialbes
            if (is_array($viewVariables) && !is_null($viewVariables)) {
            	foreach ($viewVariables as $variableName => $viewVariable) {
            		$this->view->setVariable($variableName, $viewVariable);
            	}
            }
        } 
        
        /**
         * Prepare all the variables for the controller.
         */        
        private function prepareVariables () {
            $modulesDir = PathService::getModulesDir();
            $moduleName = MvcReg::getModuleName();
            $this->_modelDir      = $modulesDir . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . "models"; 
            $this->_controllerDir = $modulesDir . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . "controllers";
            $this->_viewDir       = $modulesDir . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . "views";
        }
        
        /**
         * Set the default view.
         */
        function setDefaultView()
        {
            if (file_exists(MvcReg::getActionViewFile())) {
                $this->view->setViewFilePath(MvcReg::getActionViewFile());
            } else {
            	$actionViewFile = $this->toLowerCaseViewFilename(MvcReg::getActionViewFile());
            	if (file_exists($actionViewFile)) {
            		$this->view->setViewFilePath($actionViewFile);
            	} else {
            		//This is for backward compatiability
            		if (file_exists(MvcReg::getViewFile())) {
                		$this->view->setViewFilePath(MvcReg::getViewFile());
            		} else {
            			$viewFile = $this->toLowerCaseViewFilename(MvcReg::getViewFile());
            			$this->view->setViewFilePath($viewFile);
            		}
            	}
            }
        }

        /**
         * Set the default model.
         */
        protected function setDefaultModel()
        {
            if (file_exists(MvcReg::$_modelFile)) {
                require_once (MvcReg::$_modelFile); 
                $this->model = new MvcReg::$_modelClassName();
                $this->model->initialDB();
            }
        }
        
        /**
         * Set the parameters.
         * @param array $params
         */        
        public function setParams($params)
        {
            $this->params = $params;
        }

        /**
         * Get the parameters.
         * @return array $params
         */
        public function getParams()
        {
            return $this->params;
        }
        
        /**
         * Get the model.
         * @return object $model
         */
        public function getModel() {
            return $this->model;
        }
	
        /**
         * Set the model.
         * @param object $model 
         */
        public function setModel($model) {
            $this->model = $model;
            $this->model->initialDB();
        }
        
        
        /**
         * Get the view.
         * @return object $view
         */
        public function getView() {
            return $this->view;
        }



        /**
         * Set the view.
         * @param object $view AppView
         */
        public function setView($view) {
            $this->view = $view;
        }

        /**
         * 
         * This function can change to any view file name
         * @param string $moduleName
         * @param string $viewName
         * @param string $controllerName
         */
        public function switchView($moduleName, $viewName, $controllerName = NULL){
            $viewClassName = $viewName . self::VIEW_POSTFIX;
            if (is_null($controllerName)) {         
            	$viewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR
                      	  . "views". DIRECTORY_SEPARATOR . $viewClassName .".php";
            } else {
            	$viewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR
                      	  . "views". DIRECTORY_SEPARATOR . $controllerName. DIRECTORY_SEPARATOR . $viewClassName .".php";            	
            }
            $this->view->setViewFilePath($viewFile);
        }
        
        /**
         * Call another action within the same controller 
         * @param string $actionName
         */
        public function callAction($actionName){
            $controllerName = MvcReg::getControllerName();
            $moduleName = MvcReg::getModuleName();
            
            $actionViewClassName = $this->getActionViewClassName($moduleName, $controllerName, $actionName);
            $actionViewFile = $this->getActionViewFile($moduleName, $controllerName, $actionName);
            
            MvcReg::setActionViewClassName($actionViewClassName);
            MvcReg::setActionViewFile($actionViewFile); 
            
            $action = $actionName.self::ACTION_POSTFIX;
            $this->setDefaultView();
            $this->$action();
        }
        
        /**
         * Get the current action URL
         * @see framework\core\PathService::getFormActionURL()
         * @param boolean $isDirective Determine if using a directory or just query string in the URL.
         */        
        public function getCurrentActionURL($isDirective = False)
        {
            $moduleName = MvcReg::getModuleName();
            $controllerName = MvcReg::getControllerName();
            $actionName = MvcReg::getActionName();
            $url = PathService::getFormActionURL($moduleName, $controllerName, $actionName, null, $isDirective);
            return $url;
        }
    
        /**
         * Get the folder that saves the view files on the server
         * @param string
         */
        protected function getViewDir() {
            return $this->_viewDir;
        }
        
        /**
         * Get the folder that saves the controller files on the server
         * @return string
         */
        protected function getControllerDir() {
            return $this->_controllerDir;
        }
        
        /**
         * Get the folder that saves the model files on the server
         * @return string
         */
        protected function getModelDir(){
            return $this->_modelDir;
        }
        
        /**
         * Get the action view class name.
         * @param string $moduleName
         * @param string $controllerName
         * @param string $actionName
         * @return string
         */
        private function getActionViewClassName($moduleName, $controllerName, $actionName) {
            $viewArray = RouterHelper::getActionViewData($moduleName, $controllerName, $actionName);
            return $viewArray[0];
        }

        /**
         * Get the action view file.
         * @param string $moduleName
         * @param string $controllerName
         * @param string $actionName
         * @return string
         */
        private function getActionViewFile($moduleName, $controllerName, $actionName) {
            $viewArray = RouterHelper::getActionViewData($moduleName, $controllerName, $actionName);
            return $viewArray[1];
        }
        
        /**
         * Get the lower cased view file path
         * 
         * @param string $viewFile
         * @return string
         */
        private function toLowerCaseViewFilename($viewFile) {
        	$elems = explode(DIRECTORY_SEPARATOR, $viewFile);
        	$viewFilename = ucfirst($elems[count($elems)-1]);
        	$elems[count($elems)-1] = $viewFilename;
        	$path = join (DIRECTORY_SEPARATOR, $elems);
        	return $path;
        }
        

}

?>
