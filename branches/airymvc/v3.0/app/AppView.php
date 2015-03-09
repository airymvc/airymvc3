<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */


/**
 * This is the controller class that is used for intializing the instance and set variables.
 *
 * @package framework\app\AppView
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AppView extends AbstractView{

        /**
         * This is the viewfilepath that will be used
         * 
         * @var string $_viewFilePath
         */
	    public  $_viewFilePath;
        
        
        /**
         * Variables that have been set to this view are saved in an array
         * 
         * @var array $_variables
         */
        protected $_variables;        
        
        /**
         *
         * Determine if the plugins will be added
         * 
         * @var Boolean $_hasScript Default value = false.
         */
        protected $_hasScript  = false;
        
        /**
         * Determine if the view si inside a layout. Default value = false.
         *
         * @var Boolean $_inLayout
         */
        protected $_inLayout = false;

        /**
         * Save the language service object.
         *
         * @var object $_languageService
         */        
        protected $_languageService;
        
        /**
         * Determine if the view has no doctype. Default value = false.
         *
         * @var Boolean $_noDoctype
         */
        protected $_noDoctype = false;

        /**
         * Save doctype value. Default value = NULL.
         *
         * @var string $_doctype
         */
        protected $_doctype = NULL;

        /**
         * Save view script value. 
         *
         * @var string $_viewScripts
         */
        protected $_viewScripts;
        
        
		public function __construct()
		{
          	$this->_viewFilePath = NULL;
          	//$this->_path   = PathService::getInstance();
          	$this->_languageService = Language::getInstance();
			
          	$existed = in_array("airy.view", stream_get_wrappers());
		  	if ($existed) {
    		  	stream_wrapper_unregister("airy.view");
		  	}
          	stream_wrapper_register('airy.view', 'StreamHelper');
		}	
        
        /**
         * This method render the view.
         *
         * @throws Exception 
		 * @var  $httpServerHost absolute host url.
		 * @var  $serverHost absolute host path.
		 * @var  $ABSOLUTE_URL absolute host url.
		 * @var  $SERVER_HOST absolute host path.
		 * @var  $LEAD_FILENAME leading filename ex: index.php.
		 * 
         */
		public function render() {
           try {
                if (!is_null($this->_viewFilePath) && file_exists($this->_viewFilePath)) {
   
                    if (!is_null($this->_variables)) {
                        foreach ($this->_variables as $name=>$value)
                        {
                            if ($value instanceof UIComponent || $value instanceof JsUIComponentInterface) {
                                $htmlValue        = $value->render();
                                $newHtmlValue     = $this->_languageService->replaceWordByKey($htmlValue);
                                ${$name}          = $newHtmlValue; 
                                $this->hasAnyScript($newHtmlValue);
                            } else {
                                ${$name} = $value;
                            }         
                        }
                    }
                       
                    $httpServerHost   = PathService::getAbsoluteHostURL();
                    $serverHost       = PathService::getAbsoluteHostPath();
                    
                    $ABSOLUTE_URL     = PathService::getAbsoluteHostURL();
                    $SERVER_HOST      = PathService::getAbsoluteHostPath();
                    $LEAD_FILENAME    = Config::getInstance()->getLeadFileName();
                                                                 
                    $viewContent = file_get_contents($this->_viewFilePath);
                    $this->hasAnyScript($viewContent);
                    
                    //hasScript check if there is a need for javascript library to be added in
                    //If there is no javascript UI component, but setScript == true, we still
                    //add plugins. Otherwise, we simply do not add any libraries.
                    if ($this->_hasScript) {
                        //add plugins
                        $viewContent = $this->addPluginLib($viewContent);                       
                    }
                      
                    //$viewContent = $this->_languageService->replaceWordByKey($viewContent);
                    
                    //Check if inserting doctype at the beginning of the view content
                    if (!$this->_inLayout) {
                        if (!$this->_noDoctype) {
                    		if (is_null($this->_doctype)) {
                    			$this->setDoctype();
                    		}
                    		$viewContent = $this->_doctype . $viewContent;
                    	}  
                    } 
                    
                    $fp = fopen("airy.view://view_content", "r+");                    
                    fwrite($fp, $viewContent);
                    fclose($fp);

                    if (!$this->_inLayout) {
                    	//use ob_start to push all include files into a buffer 
                    	//and then call the callback function replaceLanguageWords
                    	//only use stream writter cannot fulfill this
                    	ob_start(array($this, 'replaceLanguageWords'));
                        include "airy.view://view_content";  
                        ob_end_flush(); 
                    } else {
                    	$this->_viewScripts = file_get_contents("airy.view://view_content");
                        return $this->_viewScripts;
                    }
                    
                } else {
                    throw new Exception("No View File {$this->_viewFilePath} Existed!");
                }
            } catch (Exception $e) {
                echo 'Exception: ',  $e->getMessage(), "\n";
            }
		}        
	

		/**
		 * This method gets the view file path.
		 * 
	 	 * @return the $viewfilepath
	 	 */
		public function getViewfilepath() {
			return $this->_viewFilePath;
		}

		/**
		 * This method set the view variables. The same as setVar method.
		 * @see setVar method
		 *
		 * @param string $variableName
		 * @param object $value
		 */
        public function setVariable($variableName, $value) {
              $this->_variables[$variableName] = $value;
        }
        
		/**
		 * This method set the view variables. The same as setVariable method.
		 *
		 * @param string $variableName
		 * @param object $value
		 */       
        public function setVar($variableName, $value) {
              $this->_variables[$variableName] = $value;
        }
        
		/**
		 * This method set the view file path.
		 * 
	 	 * @param string $viewFilePath
	 	 */
		public function setViewFilePath($viewFilePath) {
			$this->_viewFilePath = $viewFilePath;
		}

        
        /**
         * Check if there is any javascript in the view
         * 
         * @param string $html
         * 
         */
        protected function hasAnyScript($html) {

            if (!$this->_hasScript) {
                preg_match_all('/<(script)(.[^><]*)?>/imU', $html, $matches); 
                $this->_hasScript = empty($matches[0]) ? false : true;
            }
            
        }
        
        /**
         * This method set true|false to the $_hasScript property. If there is script plugin, set value = TRUE.
         *
         */
        public function setScriptPlugin() {
            $this->_hasScript = true;
        }
        
        /**
         * Get the plugin libraries that are set in config.ini.
         *
         * @return string the plugin string that will be appended in the view.
         */
        protected function getPluginLib() {
            
            $pluginStr = "";
            //Get the array of css and javascript addresses from config.ini
            $libs    = Config::getInstance()->getScriptPlugin();
            if (isset($libs['css'])) {
            	$cssLibs = $libs['css'];
            
            	foreach ($cssLibs as $cssLib) {
                	$pluginStr .= sprintf("<link rel='stylesheet' type='text/css' href='%s'>", $cssLib);
            	}
            }
            
            if (isset($libs['script'])) {
            	$JsLibs  = $libs['script'];
            	foreach ($JsLibs as $JsLib) {
                	$pluginStr .= sprintf("<script src='%s'></script>", $JsLib);
            	} 
            }
            return $pluginStr;
        }
        
        /**
         * Add plugin library string and return html content
         * 
         * @param string $content
         * @return string the content of the view
         */
        protected function addPluginLib($content){
            
            $pluginStr = $this->getPluginLib();
            
            preg_match_all('/<\s*(title)(.[\s*^><]*)?>(.[^<]*)?<\s*\/(title)\s*>/imU', $content, $matches, PREG_PATTERN_ORDER); 
            $title = isset($matches[0][0]) ? $matches[0][0] : null;
   
            if (!is_null($title)) {
                //Completed html; attach after <title></title>
                $replaceText = $title . $pluginStr;
                $content = str_replace($title, $replaceText, $content);
            } else {
                //Not completed html; directly attach to the begining
                $content = $pluginStr . $content;
            }
            
                        
            return $content;
        }
        
        /**
         * Set if the view is in a layout content value.
         * @param boolean $boolFlag
         * @return boolean
         */
        public function setInLayout($boolFlag) {
            $this->_inLayout = $boolFlag;
        }
        
        /**
         * Get the view variables.
         *
         * @return array the view variables.
         */        
        public function getViewVariables() {
            return $this->_variables;
        }
        
        /**
         * Set the doctype content value. The default value = NULL.
         * 
         * @param string $doctype the doctype value. 
         */
        public function setDoctype($doctype = NULL) {
        	$doctypeHandler = new Doctype();
        	$this->_doctype = $doctypeHandler->getDoctype($doctype);
        }
        
        /**
         * Determine if the view has no doctype. Return the true|false value.
         *
         * @return boolean
         */
        public function noDoctype() {
			$this->_noDoctype = true;
        }
        
        /**
         * Get view script property
         *
         * @return string the view scripts
         */
        public function getViewScripts() {
        	return $this->_viewScripts;
        }
        /**
         * call back function 
         * @param string $buffer
         */
        public function replaceLanguageWords($buffer) {
        	return $this->_languageService->replaceWordByKey($buffer);
        }
        
	
}