<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This is a special controller for ACL.
 *
 * @package framework\app\library\acl\AclController
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AclController extends AbstractController {

	/**
	 * @var object $_loginForm
	 */
    protected $_loginForm;
    
    /**
     * @var string $_loginFormVariableName
     */
    protected $_loginFormVariableName;
    
    /**
     * The ACL object.
     * @var object $_acl
     */
    protected $_acl;

    /**
     * Initialize the controller instance.
     * @param array $params
     * @param array view variables.
     */
    public function initial($params, $viewVariables = null) {
    	parent::initial($params, $viewVariables);
    	$this->_acl = new AclComponent($this->view);
    } 

    /**
     * Sign in action
     */
    public function signInAction() {
        $this->_acl->signIn();
    }
    
    /**
     * login out action
     */    
    public function logoutAction() {
        $this->_acl->loginOut();
    }
    
    /**
     * Login error action
     */    
    public function loginErrorAction() {
        $this->_acl->loginError();
    }
    
    /**
     * Login action
     */
    public function loginAction() {
        $this->_acl->login();
    }
    
    /**
     * Get the ACL component.
     * @return object
     */
    public function getAclComponent() {
    	return $this->_acl;
    }

    /**
     * Login Form setter functions
     */
    public function setLoginFormVariableName($loginFormVariableName) {
    	$this->_loginFormVariableName = $loginFormVariableName;
    }
    
}
?>

