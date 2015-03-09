<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\app\library\ui\html\form\validation\ValidatorInterface
 */
require_once 'ValidatorInterface.php';


/**
 * The abstract class for validation.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\AbstractValidator
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class AbstractValidator implements ValidatorInterface {

	/**
	 * @var array $_validRules
	 */
    protected $_validRules;
    
    /** 
     * @var string $_error
     */
    protected $_error;
    
    /**
     * @var string $_hasRequire
     */
    protected $_hasRequire = 'require';
    
    /**
     * @var string $_defaultMsg
     */
    protected $_defaultMsg = "ERROR!";

    /**
     * @see ValidatorInterface::setRequireValid()
     */
    public function setRequireValid($errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $this->_validRules[$this->_hasRequire] = array(0 => true, 1 => $errorMsg);
    }

    /**
     * @see ValidatorInterface::setCustomValid()
     */
    public function setCustomValid($ruleClassName, $check, $errorMsg = null) {
        $errorMsg = (is_null($errorMsg)) ? $this->_defaultMsg : $errorMsg;
        $rule = new $ruleClassName();
        $rule->setRule($check);
        $this->_validRules[] = array(0 => $rule, 1 => $errorMsg);
    }

    /**
     * Reset the validation rules.
     */
    public function resetValid() {
        $this->_validRules = array();
    }

    /**
     * @see ValidatorInterface::validate()
     */
    public function validate($value) {
        $this->_error = array();
        foreach ($this->_validRules as $type => $check) {
            if ($type == $this->_hasRequire) {
                if (empty($value) || is_null($value)) {
                    $this->_error[] = $check[1];
                }
            }
            if ($check[0] instanceof RuleInterface) {
                if (!$check[0]->validRule($value)) {
                    $this->_error[] = $check[1];
                }
            }
        }

        if (!is_null($this->_error) && !empty($this->_error) && !isset($this->_error)) {
            return true;
        }

        return $this->_error;
    }

}

?>
