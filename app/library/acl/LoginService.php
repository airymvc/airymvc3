<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This is the service that handle for authentication (login) related function.
 *
 * @package framework\app\library\acl\LoginService
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class LoginService {

	/**
	 *  @var object The instance of the class itself.
	 */
    private static $instance; 
    
    /**
     *  Use Singleton pattern here
     *  @return object The instance of the class itself.
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }    
        
        return self::$instance;
    }  
      
    /**
     * Get the user id.
     * @param string $moduleName The default value = NULL
     * @return string The user id value inside the session.
     */
    public function getLoginUserId($moduleName = NULL)
    {
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::UID];
        
    }
    
    /**
     * Get the user id.
     * @param string $moduleName The default value = NULL
     * @return string The encrypted user id value in the session.
     */
    public function getEncryptLoginUserId ($moduleName = null){
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::ENCRYPT_UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::ENCRYPT_UID];        
    }
    
    /**
     * Get the value for checking if the user is logined.
     * @param string $moduleName The default value = NULL
     * @return string The isLogin value in the session.
     */
    public function isLogin($moduleName = null)
    {
        if (!is_null($moduleName)) {
            return $_SESSION[$moduleName][Authentication::UID];
        }
        
        $currentModuleName = MvcReg::getModuleName();
        return $_SESSION[$currentModuleName][Authentication::UID];        
    }
    
    /**
     * setLogin - save the session data with uid, moduleName
     * @param string $uid
     * @param string $moduleName
     */
    public function setLogin($moduleName, $uid) {

            $_SESSION[$moduleName][Authentication::UID] = $uid;
            $_SESSION[$moduleName][Authentication::ENCRYPT_UID] = Base64UrlCode::encrypt($uid);
            $_SESSION[$moduleName][Authentication::IS_LOGIN] = true;
            $_SESSION[Authentication::UID]['module'] = $moduleName;

    }
    
    
}

?>
