<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * The interface of the validator.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\ValidatorInterface
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
interface ValidatorInterface {
    
	/**
	 * @param string $errorMsg
	 */
    public function setRequireValid($errorMsg = null);
    
    /**
     * @param string $methodName
     * @param object $object
     * @param string $errorMsg
     */
    public function setCustomValid($methodName, $object, $errorMsg = null);
    
    
    /**
     * @param string $value
     */
    public function validate($value);
    
}

?>
