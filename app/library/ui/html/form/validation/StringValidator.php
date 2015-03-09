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
 * Validation rule for checking string
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\StringValidator
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class StringValidator extends AbstractValidator{
    
	/**
	 * @param int $maxLength
	 * @param string $errorMsg
	 */
    public function setMaxLengthValid($maxLength, $errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new StrMaxLengthRule();
        $rule->setRule($maxLength);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    }
    
    /**
     * @param int $minLength
     * @param string $errorMsg
     */
    public function setMinLengthValid($minLength, $errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new StrMinLengthRule();
        $rule->setRule($minLength);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    } 
    
    /**
     * @param string $pattern
     * @param string $errorMsg
     */
    public function setPatternValid($pattern ,$errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new StrPatternRule();
        $rule->setRule($pattern);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    }       

}

?>
