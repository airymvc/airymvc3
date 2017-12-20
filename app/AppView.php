<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

namespace airymvc\app;

require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Mvc.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'AiryException.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib'. DIRECTORY_SEPARATOR . 'view'. DIRECTORY_SEPARATOR . 'StreamHelper.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib'. DIRECTORY_SEPARATOR . 'view'. DIRECTORY_SEPARATOR . 'TemplateMapper.php';
use airymvc\core\Mvc;
use airymvc\core\AiryException;
use airymvc\app\lib\view\StreamHelper;
use airymvc\app\lib\view\TemplateMapper;

/**
 * This is the controller class that is used for intializing the instance and set variables.
 *
 * @package airymvc\app\AppView
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AppView {
        
        /**
         * Variables that have been set to this view are saved in an array
         * 
         * @var array $variables
         */
        protected $vars; 

        protected $template;
        
        /**
         * Save view script value. 
         *
         * @var string $viewScripts
         */
        protected $viewScripts;
        
        
        protected $request;
        
        
        protected $params;
        
		public function __construct() {
          	$existed = in_array("airy.view", stream_get_wrappers());
		  	if ($existed) {
    		  	stream_wrapper_unregister("airy.view");
		  	}
          	stream_wrapper_register('airy.view', 'airymvc\app\lib\view\StreamHelper');
		}	
        
        /**
         * This method render the view.
         *
         * @throws AiryException 
		 * 
         */
		public function render() {
           try {
           		$viewElems = explode(DIRECTORY_SEPARATOR, Mvc::viewFile());
           		$viewElems[3] = lcfirst($viewElems[3]);
           		$viewElems[4] = lcfirst($viewElems[4]);
				$fallBackViewFile = join(DIRECTORY_SEPARATOR, $viewElems);
				$viewFile = Mvc::viewFile();
				if (!file_exists($viewFile)) {
					$viewFile = $fallBackViewFile;
				}
                if (file_exists($viewFile)) {
                	$templateScript = ""; 
                	$viewKey = NULL;
                	if (!is_null($this->template)) {
                		$urlMapParam = isset($this->params[$this->template->urlMapParam()]) ? $this->params[$this->template->urlMapParam()] : NULL;
                		$templateScript = $this->template->render($this->request, $urlMapParam);
                		$viewKey = $this->template->viewKey();
                	}  
                	

                	
                    $viewContent = file_get_contents($viewFile);
                    
                    if (!is_null($viewKey)) {
                    	$viewContent = str_replace("%{".$viewKey."}%", $viewContent, $templateScript);
                    }
                    
                    //set view variables to view
                    if (!is_null($this->vars)) {
                    	foreach ($this->vars as $name => $value)
                    	{
                    		${$name} = $value;
                    	}
                    }              

                    $fp = fopen("airy.view://view_content", "r+");                    
                    fwrite($fp, $viewContent);
                    fclose($fp);
                    
                    include "airy.view://view_content";

                } else {
                    throw new AiryException("No View File {$viewFile} Existed.");
                }
            } catch (Exception $e) {
                echo 'Exception: ',  $e->getMessage(), "\n";
            }
		}        
	
        
		/**
		 * This method set the view variables. The same as setVariable method.
		 *
		 * @param string $variableName
		 * @param object $value
		 */       
        public function setVar($varName, $value) {
              $this->vars[$varName] = $value;
        }
        
        
        /**
         * This method set the request to view.
         *
         * @param Request $request
         */
        public function setRequest($request) {
        	$this->request = $request;
        }
        
        
        /**
         * This method set the params.
         *
         * @param array $params
         */
        public function setParams($params) {
        	$this->params = $params;
        }
        

        /**
         * This method set the template of the view.
         *
         * @param string $tpl
         */
        public function setTemplate($tpl) {
        	$this->template = $tpl;
        }
        
        /**
         * Get the view variables.
         *
         * @return array the view variables.
         */        
        public function viewVariables() {
            return $this->variables;
        }
        
//         /**
//          * Set the doctype content value. The default value = NULL.
//          * 
//          * @param string $doctype the doctype value. 
//          */
//         public function setDoctype($doctype = NULL) {
//         	$doctypeHandler = new Doctype();
//         	$this->_doctype = $doctypeHandler->getDoctype($doctype);
//         }
        

        /**
         * Get view script property
         *
         * @return string the view scripts
         */
        public function viewScripts() {
        	return $this->viewScripts;
        }
        
        
        /**
         * call back function 
         * @param string $buffer
         */
        public function replaceLanguageWords($buffer) {
        	return $this->_languageService->replaceWordByKey($buffer);
        }
        
	
}