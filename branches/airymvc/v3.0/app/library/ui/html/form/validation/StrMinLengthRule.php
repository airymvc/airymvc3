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
 * Valiation rule for string pattern
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\StrMinLengthRule
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class StrMinLengthRule implements RuleInterface {

   /**
    * @var int $_min
    */	
   private $_min; 
   
   /**
    * @param string $pattern 
    */ 
   public function setRule($min){
       $this->_min = $min;
   }
   
   /**
    * @param string $input
    * @return boolean 
    */ 
   public function validRule($input) {
       if (strlen($input) < $this->_min){
           return false;  
       }
       return true;
   }
}

?>
