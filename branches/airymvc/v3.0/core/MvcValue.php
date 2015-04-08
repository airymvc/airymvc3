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
 * This class saves application, action, module, controller related data
 *
 * @package airymvc\core\MvcValue
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 *
 */
class MvcValue{
	
	protected $appName;
	
	protected $appRelativePath;
	
	protected $moduleName;
	 
	protected $controllerClassName;
	 
	protected $controllerName;
	
	protected $controllerFile;
	 
	protected $actionName;
	
	protected $actionFunctionName;
	
    protected $viewClassName;
	
    protected $viewName;
    
    protected $viewFile;

    protected $modelClassName;
	
    protected $modelName;
    
    protected $modelFile;
	
	public function setNames($appRelativePath, $appName, $moduleName, $controllerName, $actionName) {
		//set app
		$this->setAppName($appName);
		
		$this->setAppRelativePath($appRelativePath);
		
		//set module
		$this->setModuleName($moduleName);
		
		//set controller
		$this->setControllerName($controllerName);
		$this->setControllerClassName($controllerName."Controller");
		$controllerFile = $moduleName 
		                . DIRECTORY_SEPARATOR . "controllers" 
		                . DIRECTORY_SEPARATOR . $controllerName."Controller.php";
		$this->setControllerFile($controllerFile);
		
		//set Action
		$this->setActionName($actionName);
		$this->setActionFunctionName($actionName."Action");

		//set View
		$ucActionName = ucfirst($actionName);
		$this->setViewName($ucActionName);
		$this->setViewClassName($ucActionName."View");
		$viewFile = $moduleName
		          . DIRECTORY_SEPARATOR . "views"
		          . DIRECTORY_SEPARATOR . $controllerName
				  . DIRECTORY_SEPARATOR . $ucActionName."View.php";
		$this->setViewFile($viewFile);

		//set Model
		$this->setModelName($controllerName);
		$this->setModelClassName($controllerName."Model");
		$modelFile = $moduleName
		           . DIRECTORY_SEPARATOR . "models"
                   . DIRECTORY_SEPARATOR . $controllerName."Model.php";
		$this->setModelFile($modelFile);
	}
	
	public function appName() {
		return $this->appName;
	}
	
	public function setAppName($appName) {
		$this->appName = $appName;
		return $this;
	}
	

	public function moduleName() {
		return $this->moduleName;
	}
	
	public function setModuleName($moduleName) {
		$this->moduleName = $moduleName;
		return $this;
	}

	public function controllerClassName() {
		return $this->controllerClassName;
	}
	
	public function setControllerClassName($controllerClassName) {
		$this->controllerClassName = $controllerClassName;
		return $this;
	}
	
	public function controllerName() {
		return $this->controllerName;
	}
	
	public function setControllerName($controllerName) {
		$this->controllerName = $controllerName;
		return $this;
	}

	
	public function actionName() {
		return $this->actionName;
	}
	
	public function setActionName($actionName) {
		$this->actionName = $actionName;
		return $this;
	}
	
	public function viewClassName() {
		return $this->viewClassName;
	}
	
	public function setViewClassName($viewClassName) {
		$this->viewClassName = $viewClassName;
		return $this;
	}
	
	public function viewName() {
		return $this->viewName;
	}
	
	public function setViewName($viewName) {
		$this->viewName = $viewName;
		return $this;
	}
	
	public function modelClassName() {
		return $this->modelClassName;
	}
	
	public function setModelClassName($modelClassName) {
		$this->modelClassName = $modelClassName;
		return $this;
	}
	
	public function modelName() {
		return $this->modelName;
	}
	
	public function setModelName($modelName) {
		$this->modelName = $modelName;
		return $this;
	}
	public function controllerFile() {
		return $this->controllerFile;
	}
	public function setControllerFile($controllerFile) {
		$this->controllerFile = $controllerFile;
		return $this;
	}

	public function viewFile() {
		return $this->viewFile;
	}
	public function setViewFile($viewFile) {
		$this->viewFile = $viewFile;
		return $this;
	}
	public function modelFile() {
		return $this->modelFile;
	}
	public function setModelFile($modelFile) {
		$this->modelFile = $modelFile;
		return $this;
	}
	public function actionFunctionName() {
		return $this->actionFunctionName;
	}
	public function setActionFunctionName($actionFunctionName) {
		$this->actionFunctionName = $actionFunctionName;
		return $this;
	}
	public function appRelativePath() {
		return $this->appRelativePath;
	}
	
	public function setAppRelativePath($relativePath) {
		$this->appRelativePath = $relativePath;
		return $this;		
	}
	
	
	
}
?>
