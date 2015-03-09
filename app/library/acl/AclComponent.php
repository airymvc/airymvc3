<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\core\AclUtility
 */
require_once ('AclUtility.php');

/**
 * This is for preparing the ACL object for authentication (login).
 *
 * @package framework\app\library\acl\AclComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AclComponent {
	
	const MD5 = "MD5";
	const ACTION_POSTFIX = "Action";
	
	/**
	 * @var object $_loginForm The form object is used for view.
	 */
	public $_loginForm;
	
	/**
	 * @var string $_moduleName The module name.
	 */
	private $_moduleName;
	
	/**
	 * @var string $_formId The form id.
	 */
	private $_formId;
	
	/**
	 * @var string $_formName The form name.
	 */
	private $_formName;
	
	/**
	 * @var string $_uidLabel The label of user id of the form.
	 */
	private $_uidLabel;
	
	/**
	 * @var string $_pwdLabel The label of password of the form.
	 */
	private $_pwdLabel;
	
	/**
	 * @var array $_formLayout An array saves form's layouts.
	 */	
	private $_formLayout = array();
	
	/**
	 * @var string $_loginMsgId The login message id.
	 */
	private $_loginMsgId;
	
	/**
	 * @var object $_view The view object.
	 */
	protected $_view;
	
	/**
	 * @var array $params Save the parameters.
	 */
	public $params = array();

	/**
	 * @var object $acDb The database object used for ACL.
	 */
	protected $acDb;
	
	/**
	 * @var array $acDbArray The array saves database objects that are used for ACL.
	 */
	protected $acDbArray;
    
	/**
	 * Constructor
	 */	
	public function __construct($view) {
		//prepare db
		$this->initialDB();
		
		if ($view instanceof AppView) {
			$this->_view = $view;
		}else {
			throw new Exception("Acl Component does not get correct view object");
		}
		$this->params = Parameter::getParams();
		
	}
	
	/**
	 * Initialize the database object.
	 * @throws AiryException The exception is thrown for database mapping.
	 */	
    public function initialDB() {
        $multiDb = DbConfig::getConfig();
        $acl              = AclUtility::getInstance();
        $this->acDbArray  = $multiDb; 
        
        //set a default value for acDb
        $moduleName = MvcReg::getModuleName();
        $mapTableId = $acl->getTableIdByModule($moduleName);
        $mapDbId    = $acl->getMapDatabaseId($mapTableId);
        if (isset($this->acDbArray[$mapDbId])) {
        	$this->acDb = $this->acDbArray[$mapDbId];
        } else {
        	throw new AiryException("Acl Xml database mapping is wrong, check 'mapping_database_id' value or your config.ini");
        }
    }
    
    /**
     * Set the database object
     * @param string $mapDbId The mapping database id that is set in acl.xml.
     */    
    public function setDb($mapDbId) {
    	$this->acDb = $this->acDbArray[$mapDbId];
    }

	/**
     * The signIn method - check with the database table with uid, pwd and mapping table 
     * and dispatch to the responding action after login.
     * 
     * @param string $uid
     * @param string $pwd
     * @param string $mapTbl
     */
    public function signIn($moduleName = null, $controllerName = null, $actionName = null) {

        $moduleName = (is_null($moduleName)) ? MvcReg::getModuleName() : $moduleName;
        $controllerName = (is_null($controllerName)) ? MvcReg::getControllerName() : $controllerName;
        $actionName = (is_null($actionName)) ? MvcReg::getActionName() : $actionName;
        
        $acl = AclUtility::getInstance();
        $tbl_id = $acl->getTableIdByModule($moduleName);
        
        //if the module is not from MvcReg or different from MvcReg, need to get the mapDbId again for reset the acDb
        if (!is_null($moduleName) || ($moduleName == MvcReg::getModuleName())) {
        	$mapDbId    = $acl->getMapDatabaseId($tbl_id);
        	$this->setDb($mapDbId);
        }
        
        $tableName = $acl->getTableById($tbl_id);
        $mapFields = $acl->getMappingFieldByTbl($tbl_id);
        
        //prepare encryption setting
        $encrytionArray = $acl->getEncrytion();
        $encrytion = $encrytionArray[$tbl_id];
        $useEcryption = (isset($encrytion['use_pwd_encryption'])) ? $encrytion['use_pwd_encryption'] : NULL;
        //This sets the default method to PHP MD5 encryption
        $encrytionOption = (isset($encrytion['encrytion_option'])) ? $encrytion['encrytion_option'] : "PHP";
        $encrytionMethod = (isset($encrytion['encrytion_method'])) ? $encrytion['encrytion_method'] : "MD5";        

        $dbUid = $mapFields["user_id"];
        $dbPwd = (isset($mapFields["pwd"])) ? $mapFields["pwd"] : null;
        $dbSalt = (isset($mapFields["pwd_encrypt"])) ? $mapFields["pwd_encrypt"] : null;
        $dbIsdelete = (isset($mapFields["is_delete"])) ? $mapFields["is_delete"] : null;
        $dbIsdeleteValue = (isset($mapFields["is_delete_value"]) && !is_null($dbIsdelete)) ? $mapFields["is_delete_value"] : null;

        $params = Parameter::getParams();
        if (isset($params["{$dbUid}"]) && isset($params["{$dbPwd}"])) {
        	$uid = $params["{$dbUid}"];
        	$pwd = $params["{$dbPwd}"];
        } else {
        	throw new AiryException("Not passing the user id and password from the login form");
        }

        $mysql_results = null;
        //determine use encryption for password or not 
        if (!is_null($useEcryption) && (($useEcryption == 1) || (strtoupper($useEcryption) == "TRUE"))) {
        	$salt = "";
        	if (strtoupper($encrytionOption) == "PHP") {
        		/**
        		 * Currently, only support MD5
        		 */
        		if (strtoupper($encrytionMethod) == self::MD5) {
        			$salt = md5(trim($pwd));
        		}
        	} else {
        		$encryObj = new $encrytionOption();
        		$salt = $encryObj->$encrytionMethod(trim($pwd));
        	}
            $mysql_results = $this->getUserByUid($tableName, $dbUid, $uid, $dbIsdelete, $dbIsdeleteValue);
        } else {
            $mysql_results = $this->getUserByUid($tableName, $dbUid, $uid, $dbIsdelete, $dbIsdeleteValue);
        }
        $rows = mysql_fetch_array($mysql_results, MYSQL_ASSOC);
        $bLogin = false;
        
        if (is_array($rows)) {
        	if (!is_null($useEcryption) && (($useEcryption == 1) || (strtoupper($useEcryption) == "TRUE"))) {
            	if ($rows[$dbSalt] == $salt) {
                	$bLogin = true;
            	}
       		} else {
            	if ($rows[$dbPwd] == $pwd) {
                	$bLogin = true;
            	}
        	}
        }
        
        if ($bLogin) {
            $_SESSION[$moduleName][Authentication::UID] = $uid;
            $_SESSION[$moduleName][Authentication::ENCRYPT_UID] = Base64UrlCode::encrypt($uid);
            $_SESSION[$moduleName][Authentication::IS_LOGIN] = true;
            $_SESSION[Authentication::UID]['module'] = $moduleName;
            foreach ($rows as $key => $value) {
                    $_SESSION[$moduleName]['user'][$key] = $value;
            }
            
            $successfulArray = $acl->getSuccessfulDispatch();
            $successfulController = $successfulArray[$moduleName]['controller'];
            $successfulAction = $successfulArray[$moduleName]['action'];
            //forward to login sucessful action - this is set in the act.xml
            Dispatcher::forward($moduleName, $successfulController, $successfulAction, $params);
        } else {
            $authArray = $acl->getAuthentications();
            $loginErrorActionName = "loginErrorAction";
            if (isset($authArray[$moduleName]['login_error_action'])) {
                $loginErrorActionName = $authArray[$moduleName]['login_error_action'];
            } 
            //forward to login error action
            Dispatcher::forward($moduleName, $controllerName, $loginErrorActionName, $params);
        }
    }
    /**
     * Unset all the session values that are about the authentication (login).
     */    
    public function loginOut() {
        $moduleName = MvcReg::getModuleName();
        unset($_SESSION[$moduleName][Authentication::UID]);
        unset($_SESSION[$moduleName][Authentication::ENCRYPT_UID]);
        unset($_SESSION[$moduleName][Authentication::IS_LOGIN]);
        unset($_SESSION[Authentication::UID]['module']);
    }
    
    /**
     * Getter and setter for each form variables
     */

    /**
     * Prepare the login form object for the view.
     * 
     * @example 
     * FormLayout example:
     * 
     * array(formId      => array('<div class="class_selector">', '</div>'),
     *       elementId1  => array('<div class="elememtClass1">', '</div>'),
     *       elementId2  => array('<div class="elememtClass2">', '</div>'),
     *       ...
     *       {elementId} => array('{open_html}, {close_html})
     *      );
     *
     * @see framework\app\library\acl\LoginForm 
     * @return object The login form object.
     */
    public function prepareLoginForm() {

        $this->_moduleName = (is_null($this->_moduleName)) ? MvcReg::getModuleName() : $this->_moduleName;
        $this->_formName   = (is_null($this->_formName)) ? "system_login_form" : $this->_formName;
        $this->_formId     = (is_null($this->_formId)) ? "system_login_form" : $this->_formId;
        $this->_uidLabel   = (is_null($this->_uidLabel)) ? "%{Username}%" : $this->_uidLabel;
        $this->_pwdLabel   = (is_null($this->_pwdLabel)) ? "%{Password}%" : $this->_pwdLabel;
        $this->_loginMsgId = (is_null($this->_loginMsgId)) ? "system_login_message" : $this->_loginMsgId; 
        
        $this->_formLayout = array($this->_formId  => array("<div class='{$this->_formName}' name='{$this->_formName}'>", "</div>"));           
        $loginForm = new LoginForm($this->_formId, $this->_formName, $this->_uidLabel, $this->_pwdLabel, $this->_moduleName, $this->_formLayout, $this->_loginMsgId, null);
        return $loginForm;
    }

    /**
     * Set the login form object to the view variable and then render the view
     *
     * @param string $loginFormName The login form name; the default value = NULL
     */
    public function login($loginFormName = NULL) {
        $this->_loginForm = $this->prepareLoginForm();
    	$loginFormName = (is_null($loginFormName)) ? $this->_loginForm->getFormId() : $loginFormName;
    	
        //to generate the view
        $this->_view->setVariable($loginFormName, $this->_loginForm);
        $this->_view->render();
    } 

    /**
     * Set NULL (Reset) for all the login form attributes
     * 
     * @see AclComponent::setLoginFormOptions() Call for setting the login form options.
     *
     * @param string $moduleName Default value = NULL.
     * @param string $formId Default value = NULL.
     * @param string $formName Default value = NULL.
     * @param string $uidLabel Default value = NULL.
     * @param string $pwdLabel Default value = NULL.
     * @param array  $formLayout Default value = NULL.
     * @param string $loginMsgId Default value = NULL.
     */
    public function resetLoginForm($moduleName = NULL, $formId = NULL, $formName = NULL, $uidLabel = NULL, $pwdLabel = NULL, $formLayout = NULL, $loginMsgId = NULL) {
        $this->setLoginFormOptions($moduleName, $formId, $formName, $uidLabel, $pwdLabel, $formLayout, $loginMsgId);
    	$this->_loginForm = $this->prepareLoginForm();
        return $this->_loginForm;
    }
    
    /**
     * Render the login error in the view.
     *
     * @see AclComponent::prepareLoginForm() Prepare the login form object.
     * @see LoginForm::populateErrorMessage() Populate error messages.
     *
     * @param string $errorMessage Default value = NULL.
     * @param string $errorMsgVariableName Default value = NULL.
     * @param string $loginFormName Default value = NULL.
     */    
    public function loginError($errorMessage = NULL, $errorMsgVariableName = NULL, $loginFormName = NULL) {
    	if (is_null($this->_loginForm)) {
    		$this->_loginForm = $this->prepareLoginForm();
    	}
        $this->_loginForm->populateErrorMessage($errorMessage);
        $loginFormName = (is_null($loginFormName)) ? $this->_loginForm->getFormId() : $loginFormName;
        
        $errorMessage = is_null($errorMessage) ? "ERROR!!" : $errorMessage;
        $errorMsgVariableName = is_null($errorMsgVariableName) ? 'loginErrorMessage' : $errorMsgVariableName;
        
        $this->_view->setVariable($loginFormName, $this->_loginForm);
        $this->_view->setVariable($errorMsgVariableName, $errorMessage);
        $this->_view->render();
    }
    
    /**
     * This is the convenient method for setting all the form variables
     * @param String $moduleName Default value = NULL.
     * @param String $formId Default value = NULL.
     * @param String $formName Default value = NULL.
     * @param String $uidLabel Default value = NULL.
     * @param String $pwdLabel Default value = NULL.
     * @param array  $formLayout Default value = NULL.
     * @param String $loginMsgId Default value = NULL.
     */
    public function setLoginFormOptions($moduleName = NULL, $formId = NULL, $formName = NULL ,$uidLabel = NULL, $pwdLabel = NULL, $formLayout = NULL, $loginMsgId = NULL) {
		$this->setModuleName($moduleName);
		$this->setFormId($formId);
		$this->setFormName($formName);
		$this->setLoginMsgId($loginMsgId);
		$this->setPwdLabel($pwdLabel);
		$this->setUidLabel($uidLabel);
		$this->setFormLayout($formLayout);
    }
    
    
	/**
	 * Get the login form.
	 * @return the $_loginForm
	 */
	public function getLoginForm() {
		return $this->_loginForm;
	}

	/**
	 * Get the module name.
	 * @return the $_moduleName
	 */
	public function getModuleName() {
		return $this->_moduleName;
	}

	/**
	 * Get the login form id.
	 * @return the $_formId
	 */
	public function getFormId() {
		return $this->_formId;
	}

	/**
	 * Get the login form name.
	 * @return the $_formName
	 */
	public function getFormName() {
		return $this->_formName;
	}

	/**
	 * Get the login form's user id label .
	 * @return the $_uidLabel
	 */
	public function getUidLabel() {
		return $this->_uidLabel;
	}

	/**
	 * Get the login form's password label 
	 * @return the $_pwdLabel
	 */
	public function getPwdLabel() {
		return $this->_pwdLabel;
	}

	/**
	 * Get all the form layouts.
	 * @return the $_formLayout
	 */
	public function getFormLayout() {
		return $this->_formLayout;
	}

	/**
	 * Get the login message id.
	 * @return the $_loginMsgId
	 */
	public function getLoginMsgId() {
		return $this->_loginMsgId;
	}

	/**
	 * Set the login form.
	 * @param object $loginForm
	 */
	public function setLoginForm($loginForm) {
		$this->_loginForm = $loginForm;
	}

	/**
	 * Set the module name.
	 * @param string $moduleName
	 */
	public function setModuleName($moduleName) {
		$this->_moduleName = $moduleName;
	}

	/**
	 * Set the form id.
	 * @param string $formId
	 */
	public function setFormId($formId) {
		$this->_formId = $formId;
	}

	/**
	 * Set the form name value.
	 * @param string $formName
	 */
	public function setFormName($formName) {
		$this->_formName = $formName;
	}

	/**
	 * Set user id label value.
	 * @param string $uidLabel
	 */
	public function setUidLabel($uidLabel) {
		$this->_uidLabel = $uidLabel;
	}

	/**
	 * Set all the form layouts.
	 * @param string $pwdLabel
	 */
	public function setPwdLabel($pwdLabel) {
		$this->_pwdLabel = $pwdLabel;
	}

	/**
	 * Set all the form layouts.
	 * @param array() $formLayout
	 */
	public function setFormLayout($formLayout) {
		$this->_formLayout = $formLayout;
	}

	/**
	 * Set the login message id.
	 * @param string $loginMsgId The login message id value.
	 */
	public function setLoginMsgId($loginMsgId) {
		$this->_loginMsgId = $loginMsgId;
	}

	/**
	 * Set the view variable.
	 * @param string $variableName The view variable name.
	 * @param object $variable     The view variable.
	 */
    public function setViewVariable($variableName, $variable) {
    	$this->_view->setVariable($variableName, $variable); 
    }
    
    /**
     * Get the database query result of the user.
     * @param string $tableName 	The table name which maps to the user.
     * @param string $uidField 		The user id field name of the table.
     * @param string $uid 			The user id value.
     * @param string $isDeleteField The isDelete field name of the table.
     * @param string $isDelete 		The isDelete value.
     * @return object The database result.
     */    
    private function getUserByUid($tableName, $uidField, $uid, $isDeleteField = NULL, $isDelete= NULL) {

		$columns = array('*');
        if (is_null($isDeleteField) || is_null($isDelete)) {
            $where = array("AND"=>array("="=>array( $uidField  => $uid)));
        } else {
            $where = array("AND"=>array("="=>array( $uidField  => $uid),
                                        "!="=>array( $isDeleteField => $isDelete)));            
        }
        $this->acDb->select($columns, $tableName);
        $this->acDb->where($where);
        $mysqlResults = $this->acDb->execute();	
        return $mysqlResults;
    }
     
	
}

?>
