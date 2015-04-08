<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

namespace airymvc\app\lib\view;

use airymvc\app\AppView;
use airymvc\core\Mvc;

/**
 * This is the controller class that is used for intializing the instance and set variables.
 *
 * @package airymvc\app\AppView
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AppSubView extends AppView {
        
	/**
	 * Type of MvcValue
	 * 
	 * @var $subMvc;
	 */
	protected $subMvc;
	
	
	public function setSubMvc($subMvc) {
		$this->subMvc = $subMvc;
	}
        
     /**
      * This method render the view.
      *
      * @throws AiryException 
      * 
      */
	public function render() {
           try {
           	    $absViewFile = Mvc::$currentApp->documentRoot() . "/". $this->subMvc->viewFile();

                if (file_exists($absViewFile)) { 
                	$templateScript = "";
                	$viewKey = NULL;
                	if (!is_null($this->template)) {
                		$urlMapParam = isset($this->params[$this->template->urlMapParam()]) ? $this->params[$this->template->urlMapParam()] : NULL;
                		$templateScript = $this->template->render($this->request, $urlMapParam);
                		$viewKey = $this->template->viewKey();                		
                	}  
                
                	
				    //set view variables to view
                	if (!is_null($this->vars)) {
                		foreach ($this->vars as $name => $value)
                		{
                			${$name} = $value;
                		}
                	}
                	
                	ob_start();
                	include $absViewFile;
                	$viewContent = ob_get_clean();
                	
                    if (!is_null($viewKey)) {
						$viewContent = str_replace($viewKey, $viewContent, $templateScript);
                    }
                    echo $viewContent;
                    return $viewContent;

                } else {
                    throw new AiryException("No Sub View File {Mvc::viewFile()} Existed.");
                }
            } catch (Exception $e) {
                echo 'Exception: ',  $e->getMessage(), "\n";
            }
	}        
	    
	
}