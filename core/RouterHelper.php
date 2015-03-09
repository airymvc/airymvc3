<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This helper class is used for composing the data that Router needs.
 *
 * @filesource
 * @package framework\core\RouterHelper
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class RouterHelper {
    //put your code here
    private static $instance;
    
    const VIEW_POSTFIX = 'View';
    const CONTROLLER_POSTFIX = 'Controller';
    
    /**
     * Get the object of the class itself
     * @return object
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }
    
    /**
     * Get the action view data.
     * @see   RouterHelper::composeActionViewData() For knowing how data is composed.
     * @param string $moduleName The module name.
     * @param string $controllerName The controller name.
     * @param string $actionName The action name.
     */
    public static function getActionViewData($moduleName, $controllerName, $actionName) {
    	$instance = self::getInstance();
    	return $instance->composeActionViewData($moduleName, $controllerName, $actionName);
    }   
    /**
     * Convert the hyphen presentation to CamelCase.
     * @see   RouterHelper::fromHyphenToCamelCase() For knowing how it converts.
     * @param string $name The name will be converted.
     * @param boolean $hasFirstUppercase Decide if the first character is upper case in the result. 
     */
    public static function hyphenToCamelCase($name, $hasFirstUppercase = FALSE) {
    	$instance = self::getInstance();
    	return $instance->fromHyphenToCamelCase($name, $hasFirstUppercase);    	
    }
    /**
     * Convert the CamelCase presentation to hyphen.
     * @see   RouterHelper::fromCamelCaseToHyphen() For knowing how it converts.
     * @param string $name The name will be converted.
     */
    public static function camelCaseToHyphen($name) {
    	$instance = self::getInstance();
    	return $instance->fromCamelCaseToHyphen($name);    	
    }
    /**
     * Convert to CamelCase presentation.
     * @see   RouterHelper::convertToCamelCase() For knowing how it converts.
     * @param string $name The name will be converted.
     */    
    public static function toCamelCase($name) {
    	$instance = self::getInstance();
    	return $instance->convertToCamelCase($name);
    }
    /**
     * Get the controller file.
     * @see   RouterHelper::getControllerFileData() For knowing how it gets the data.
     * @param string $moduleName The module name.
     * @param string $controller The controller class name; ex: IndexController.
     */    
    public static function getControllerFile($moduleName, $controller) {
    	$instance = self::getInstance();
    	return $instance->getControllerFileData($moduleName, $controller);    	
    }
    
    /*
     * Object methods
     */ 

    /**
     * Compose action view related data.
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @return array Array with action view class name, action view file and absolute action view file path.
     */ 
    private function composeActionViewData($moduleName, $controllerName, $actionName) {          
    		//ucfirst action view class name 
    		$controllerName = $this->fromHyphenToCamelCase($controllerName, FALSE);
    		$actionName     = $this->fromHyphenToCamelCase($actionName, TRUE);
    	     
    	    $actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];
           
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            }

            //consider that $controllerName uses hyphen            
            $controllerName = $this->fromCamelCaseToHyphen($controllerName);
        	$actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];
            
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            }            
            
            //or lower case for first word
            $controllerName = $this->fromHyphenToCamelCase($controllerName, FALSE);
            $actionName     = $this->fromHyphenToCamelCase($actionName, FALSE);
            $actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            }

            //consider that $controllerName uses hyphen and lower case for first word           
            $controllerName = $this->fromCamelCaseToHyphen($controllerName);
            $actionName     = $this->fromHyphenToCamelCase($actionName, FALSE);
        	$actionViewData = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            $actionViewClassName =  $actionViewData[0];
            $actionViewFile      =  $actionViewData[1];
            $absActionViewFile   =  $actionViewData[2];     
            if (file_exists($absActionViewFile)) {
            	return array(0 => $actionViewClassName,
            			 	 1 => $actionViewFile, 
                         	 2 => $absActionViewFile);             	
            } 


            //fianlly, index_actionView.php
            $name = $this->fromHyphenToCamelCase($controllerName, TRUE) . "_" . $this->fromHyphenToCamelCase($actionName, TRUE);
            $actionViewClassName = $name . self::VIEW_POSTFIX;
            $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR . $actionViewClassName .".php";
            $absActionViewFile = PathService::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile;
                        
            return array(0 => $actionViewClassName,
            			 1 => $actionViewFile, 
                         2 => $absActionViewFile);        	
    }
    
    /**
     * Get absolute action view file path.
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @return string Absolute action view file path.
     */    
    private function composeAbsoluteActionViewFile($moduleName, $controllerName, $actionName) {
			$actionViewFiledata = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            return $actionViewFiledata[2];   	
    }
    
    /**
     * Get action view.
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @return array Array with action view file.
     */    
    private function composeActionView($moduleName, $controllerName, $actionName) {
			$actionViewFiledata = $this->composeActionViewFileData($moduleName, $controllerName, $actionName);
            return $actionViewFiledata[1];   	
    }
    
    /**
     * Compose action view class name from action name.
     * @param string $actionName
     * @return string
     */    
    private function composeActionViewClassName($actionName) {
			$actionViewClassName =  $actionName. self::VIEW_POSTFIX;
            return $actionViewClassName;
    }
    
    /**
     * Compose action view related data.
     * @see   RouterHelper::composeActionViewClassName() For knowing how data is composed. 
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @return array Array with action view class name, action view file and absolute action view file path.
     */    
    private function composeActionViewFileData($moduleName, $controllerName, $actionName) {
    		$actionViewClassName =  $this->composeActionViewClassName($actionName);
            $actionViewFile = "project". DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$moduleName .DIRECTORY_SEPARATOR. "views".DIRECTORY_SEPARATOR .$controllerName. DIRECTORY_SEPARATOR. $actionViewClassName .".php";
            $absActionViewFile = PathService::getRootDir() . DIRECTORY_SEPARATOR . $actionViewFile; 
            return array($actionViewClassName,
            			 $actionViewFile, 
                         $absActionViewFile);   	
    }
    
    /**
     * Change hyphen name into camel case
     * @param string $name
     * @param boolean $hasFirstUppercase Decide if the first character is uppercase.
     * @return string A CamelCase text.
     */
	private function fromHyphenToCamelCase($name, $hasFirstUppercase = FALSE) {
		$words = explode('-', strtolower($name));
		if (count($words) == 1) {
			$hyphenName = $this->fromCamelCaseToUcHyphen($name);
			$hyphenNameParts = explode("-", $hyphenName);
			if ($hasFirstUppercase) {
				$hyphenNameParts[0] = ucfirst($hyphenNameParts[0]);
				$oneName = join("", $hyphenNameParts);
			} else {
				if(false === function_exists('lcfirst')) {
				   $hyphenNameParts[0] = $this->lcFirst($hyphenNameParts[0]);
				} else {
				   $hyphenNameParts[0] = lcfirst($hyphenNameParts[0]);
				}
				$oneName = join("", $hyphenNameParts);				
			}
			return $oneName;
		}
		
		$camelCaseName = '';
		$index = 0;
		foreach ($words as $word) {
			if (!$hasFirstUppercase && $index ==0) {
				$camelCaseName .= trim($word);
			} else {
				$camelCaseName .= ucfirst(trim($word));
			}
			$index++;
		}
		return $camelCaseName;
	}
	
	/**
	 * Change to camel case
	 * @param string $name
	 * @return string A CamelCase text.
	 */	
	private function convertToCamelCase($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $name);
		$name = strtolower($name);
    	$words = explode('_', strtolower($name));
    	$camelCaseName = '';
    	
    	foreach ($words as $word) {
    		$camelCaseName .= ucfirst(trim($word));
    	}
    	    	
    	return $camelCaseName;
	}
	
	/**
	 * Change to hyphen name
	 * @param string $name
	 * @return string A hyphen text.
	 */	
	private function fromCamelCaseToHyphen($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','-$1', $name);
		$name = strtolower($name);    	    	
    	return $name;
	}

	/**
	 * Change to CamelCase
	 * @param string $name
	 * @return string A camel case.
	 */
	private function fromCamelCaseToUcHyphen($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $name);
		$name = strtolower($name);
		$words = explode('_', strtolower($name));    	
    	foreach ($words as $key => $word) {
    		$words[$key] = ucfirst(trim($word));
    	}
    	$name = join("-", $words);    	  	    	
    	return $name;
	}
	
	/**
	 * Get the controller file path
	 * @param string $moduleName
	 * @param string $controller
	 * @return string
	 */	
	private function getControllerFileData($moduleName, $controller) {
		$controllerfile = 'project'. DIRECTORY_SEPARATOR
						 .'modules'.DIRECTORY_SEPARATOR 
						 . $moduleName .DIRECTORY_SEPARATOR
						 .'controllers'.DIRECTORY_SEPARATOR 
						 . $controller .'.php';	
        return 	$controllerfile;
	}

	/**
	 * Make first character lower case.
	 * @param string $str
	 * @return string A text with first lower case character.
	 */
    private function lcFirst($str) {
       	$str[0] = strtolower($str[0]);
       	return (string)$str;
   	}

}

?>