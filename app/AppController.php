<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\app;

require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Framework.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Application.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Mvc.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib'. DIRECTORY_SEPARATOR . 'view'. DIRECTORY_SEPARATOR . 'AppSubView.php';
use airymvc\core\Framework;
use airymvc\core\Application;
use airymvc\core\Mvc;
use airymvc\app\lib\view\AppSubView;

/**
 * This is the controller class that is used for intializing the instance and set variables.
 *
 * @package airymvc\app\AppController
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class AppController
{
	
	protected $params;

	protected $model;
	
	protected $view;
	
	protected $request;
	
	protected $subMvc;

	
	//The constructor function for the controller
	public function init() {}
	
	
	/**
	 * Set the parameters.
	 * @param array $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}
	
	/**
	 * Set the request.
	 * @param array $params
	 */
	public function setRequest($request)
	{
		$this->request = $request;
	}
	
	/**
	 * Get the value from the http parameters.
	 * @param string $name
	 * @return object the parameter value
	 */
	public function param($name)
	{
		return $this->params[$name];
	}	
	
	/**
	 * Get the array of the http parameters.
	 * 
	 * @return object the parameter value
	 */
	public function params()
	{
		return $this->params;
	}
	
	/**
	 * Get the request.
	 * 
	 * @return object the http request
	 */
	public function request()
	{
		return $this->request;
	}
	
		
	/**
	 * Set the default view.
	 * 
	 * @param Template $template the mapping template for the view
	 */
	public function setDefaultView($template = NULL, $isSubView = FALSE)
	{
		if ($isSubView) {
			$this->view = new AppSubView();
			$this->view->setSubMvc($this->subMvc);
		} else {
			$this->view = new AppView();
		}
		$this->view->setTemplate($template);
		$this->view->setRequest($this->request);
		$this->view->setParams($this->params);
	}
	
	/**
	 * Set the default model.
	 */
	public function setDefaultModel()
	{
		if (file_exists(Mvc::modelFile())) {
			require_once (Mvc::modelFile());
			$modelClassName = Mvc::modelClassName();
			$this->model = new $modelClassName();
			$this->model->initDb();
		} else {
			error_log("expect a model file");
			//@TODO: throws an exception.
		}
	}
	

	/**
	 * Get the model.
	 * @return object $model
	 */
	public function model() {
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
	public function view() {
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
	 * Get the view.
	 * @return object $view
	 */
	public function subMvc() {
		return $this->subMvc;
	}
	
	
	
	/**
	 * Set the view.
	 * @param object $view AppView
	 */
	public function setSubMvc($subMvc) {
		$this->subMvc = $subMvc;
	}


}
?>
