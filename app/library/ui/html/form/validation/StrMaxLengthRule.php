<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\app\library\ui\html\form\validation\RuleInterface
 */
require_once 'RuleInterface.php';

/**
 * Validation rule for checking maximum length of the string
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\RuleInterface
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class StrMaxLengthRule implements RuleInterface {

  /**
   * @var inst $_max
   */
   private $_max; 
   
   /**
    * @param int $max 
    */ 
   public function setRule($max){
       $this->_max = $max;
   }
   
   /**
    * @param string $input
    * @return boolean 
    */ 
   public function validRule($input) {
       if (strlen($input) > $this->_max){
           return false;  
       }
       return true;
   }
}

?>
