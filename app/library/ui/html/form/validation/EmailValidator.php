<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\app\library\ui\html\form\validation\AbstractValidator
 */
require_once 'AbstractValidator.php';

/**
 * This is for email validation.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\EmailValidator
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class EmailValidator extends AbstractValidator{
  
	/**
	 * @var string $_hasEmail
	 */
    protected $_hasEmail = "email";
    
    /**
     * @var string $_defaultPattern
     */
    private $_defaultPattern = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/";
   
    /**
     * @param string $pattern Default value = NULL
     * @param string $errorMsg Default value = NULL
     */
    public function setEmailValid($pattern = NULL ,$errorMsg = NULL) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $pattern = (is_null($pattern)) ? $this->_defaultPattern : $pattern;
        $rule = new EmailRule();
        $rule->setRule($pattern);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    } 
    
    
    


}

?>
