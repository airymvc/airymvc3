<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * The interface for a validation rule.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\RuleInterface
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
interface RuleInterface {
    
	/**
	 * @param object $pattern
	 */
    public function setRule($pattern);
    
    /**
     * @param object $input
     */
    public function validRule($input);
}

?>
