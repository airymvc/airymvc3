<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * Valiation rule for string pattern
 *
 * @filesource
 * @package framework\app\library\ui\html\form\validation\StrPatternRule
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class StrPatternRule implements RuleInterface {
   
   /**
    * @var string $_pattern
    */
   private $_pattern; 
   
   /**
    * @param string $pattern 
    */ 
   public function setRule($pattern){
       $this->_pattern = $pattern;
   }
   
   /**
    * @param string $input
    * @return boolean 
    */ 
   public function validRule($input) {
       if (!preg_match($this->_pattern, $input)) {
           return false;  
       }
       return true;
   }
    
}

?>
