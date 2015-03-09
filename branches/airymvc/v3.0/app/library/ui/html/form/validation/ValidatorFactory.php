<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * A class is used for factory pattern of Validator.
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\ValidatorFactory
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class ValidatorFactory {
    
	/**
	 * @param string $validatorClassName
	 * @return string|NULL
	 */
    public function create($validatorClassName) {
        if (class_exists($validatorClassName)) {
            return new $validatorClassName();
        }
        
        return null;
    }
}

?>
