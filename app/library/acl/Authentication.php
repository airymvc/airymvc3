<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This is for preparing the ACL object for authentication (login).
 *
 * @package framework\app\library\acl\Authentication
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Authentication {

    const IS_LOGIN = "islogin";
    const UID = "uid";
    const ENCRYPT_UID = "encrypt_uid";
     
    //Four default login related actions
    const SIGN_IN = "signIn";
    const LOGIN = "login";
    const LOGIN_ERROR = "loginError";
    const LOGOUT = "logout";
    
    /**
     * @var array $layoutAllows
     */
    public static $layoutAllows = array();
    
    /**
     * @var string $aclXml The acl xml file.
     */    
    public static $aclXml;
    
    /**
     * The method checks the session value; the login value is set after login; the value is unset after logout
     * @param string $moduleName
     * @return boolean
     */
    public static function isLogin($moduleName) {
        /**
         * Use uid and module for now  
         */
        if (empty($_SESSION) || empty($_SESSION[$moduleName][self::IS_LOGIN]) || $_SESSION[$moduleName][self::IS_LOGIN] == false) {
            return false;
        }
        return true;
    }
    
    /**
     * Get sign in action according to module.
     * @param string $module
     * @return string The sign in action.
     */
    public static function getSignInAction($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        $action = self::SIGN_IN;
        if (isset($auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION];
        }
        return $action;
    }

    /**
     * Get the login controller.
     * @param string $module
     * @return string The ACL controller.
     */
    public static function getLoginController($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        if (is_null($auth[$module]["controller"])) {
            $message =  "Login Controller in Acl XML 'authentication' is not defined properly";
            throw new AiryException($message);
            return NULL;
        }
        return $auth[$module]["controller"];
    }

    /**
     * Get the login action.
     * @param string $module
     * @return string The login action.
     */
    public static function getLoginAction($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        $action = self::LOGIN;
        if (isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_LOGIN_ACTION];
        }
        return $action;
    }
    
    /**
     * Get the login error action.
     * @param string $module
     * @return string The login error action.
     */
    public static function getLoginErrorAction($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        $action = self::LOGIN_ERROR;
        if (isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $action = $auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION];
        }
        return $action;
    }
    
    /**
     * Get the controller that is used after sucessful.
     * @param string $module
     * @return string
     */    
    public static function getSuccessController($module) {
    	$auth = self::getAclUtitlity()->getSuccessfulDispatch();
    	$successController = NULL;
    	if (isset($auth[$module]["controller"])) {
    		$successController = $auth[$module]["controller"];
    	}
    	return $successController;
    }

    /**
     * Get the action that is called after sucessful.
     * @param string $module
     * @return string
     */
    public static function getSuccessAction($module) {
    	$auth = self::getAclUtitlity()->getSuccessfulDispatch();
    	$successAction = NULL;
    	if (isset($auth[$module]["action"])) {
    		$successAction = $auth[$module]["action"];
    	}
    	return $successAction;
    }

    /**
     * Get the actions that can be still dispatched (forwarded) before login. These are defined in the acl.xml.
     * @param string $module
     * @return array
     */
    public static function getOtherExclusiveActions($module) {
        $auth = self::getAclUtitlity()->getAuthentications();
        $actions = array();
        if (isset($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS])) {
        	foreach ($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS] as $idx => $exAction) {
           			 $actions[$idx] = $auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS][$idx];
        	}
        }
	    return $actions;
    }
    
    /**
     * Get all the actions that can be still dispatched (forwarded) before login;
     * this includes those login related and exclusive actions;
     * these are defined in the acl.xml.
     * 
     * @param string $module
     * @return array
     */
    public static function getLoginExcludeActions($module) {
        $loginActions = array();
        $auth = self::getAclUtitlity()->getAuthentications();
        if (!isset($auth[$module])) {
            $message = "Module {$module} is not defined or is mismatched in Acl XML 'authentication' section when config setting use_authentication is enable";       	
        	throw new AiryException($message);
            return NULL;
        }
        
        if (is_null($auth[$module]["controller"])) {
        	$message = "Acl XML is not defined properly, check your authentication settings for module {$module}";
        	throw new AiryException($message);
            return NULL;
        }
        if (!isset($auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::SIGN_IN] = self::SIGN_IN;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION]] = $auth[$module][AclXmlConstant::ACL_SIGN_IN_ACTION];
        }

        if (!isset($auth[$module][AclXmlConstant::ACL_LOGIN_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::LOGIN] = self::LOGIN;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_LOGIN_ACTION]] = $auth[$module][AclXmlConstant::ACL_LOGIN_ACTION];
        }

        if (!isset($auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::LOGIN_ERROR] = self::LOGIN_ERROR;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION]] = $auth[$module][AclXmlConstant::ACL_LOGIN_ERROR_ACTION];
        }
        if (!isset($auth[$module][AclXmlConstant::ACL_LOGOUT_ACTION])) {
            $loginActions[$auth[$module]["controller"]][self::LOGOUT] = self::LOGOUT;
        } else {
            $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_LOGOUT_ACTION]] = $auth[$module][AclXmlConstant::ACL_LOGOUT_ACTION];
        }
        
        //Here we deal with other exclusive actions
        if (isset($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS])) {
        	foreach ($auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS] as $idx => $exAction) {
           			 $loginActions[$auth[$module]["controller"]][$auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS][$idx]] = $auth[$module][AclXmlConstant::ACL_OTHER_EXCLUSIVE_ACTIONS][$idx];
        	}
        }

        return $loginActions;
    }
    
    /**
     * Get all the allowing rules;these are defined in the acl.xml.
     *
     * @param string $module
     * @return array
     */
    public static function getAllAllows($module) {
        $rules = self::getAclUtitlity()->getBrowseRules();
        $allows = (isset($rules[$module]))? $rules[$module]: null;
        return $allows;
    }
    
    /**
     * Get all the layout allowing actions.
     *
     * @param string $module
     * @param string $controllerName
     * @param string $actionName
     * @return array
     */    
    public static function addLayoutAllowAction($module, $controllerName, $actionName) {
    	self::$layoutAllows[$module][$controllerName][] = $actionName;
    }

    /**
     * Remove all the layout allowing actions.
     *
     * @param string $module
     * @param string $controllerName
     * @param string $actionName
     */
    public static function removeLayoutAllowAction($module, $controllerName, $actionName) {
    	foreach (self::$layoutAllows[$module][$controllerName] as $idx => $allowActionName) {
    		if ($actionName == $allowActionName) {
    			unset(self::$layoutAllows[$module][$controllerName][$idx]);
    		}
    	}
    	if (count(self::$layoutAllows[$module][$controllerName]) == 0) {
    		unset(self::$layoutAllows[$module][$controllerName]);
    	}
    	if (count(self::$layoutAllows[$module]) == 0) {
    		unset(self::$layoutAllows[$module]);
    	}
    }
    
    /**
     * Get the instance of AclUtility.
     *
     * @see framework/core/AclUtility
     * @return object
     */
    private static function getAclUtitlity() {
        $acl = AclUtility::getInstance();
    	if (!is_null(self::$aclXml)) {
    		$acl->setAclXml(self::$aclXml);
    	}
    	return $acl;
    }
    
    /**
     * Set the acl xml.
     *
     * @see framework/config/acl.xml
     * @see framework/config/example.acl.xml
     */    
    public static function setAclXml($xml) {
    	self::$aclXml = $xml;
    }
    

}

?>