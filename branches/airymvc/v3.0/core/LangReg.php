<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This class handles the language codes
 *
 * @package framework\core\LangReg
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class LangReg {
    
     static $_languageCode;
     const LANGUAGE_CODE = 'language_code';
     
     /**
      * This method sets the language code
      * @param array $languageCode all the language codes
      */
     public static function setLanguageCode($languageCode) {
          self::$_languageCode = $languageCode;
     }
     
     /**
      * This method gets all the language codes.
      *
      * @return array all the language codes
      */
     public static function getLanguageCode() {
          return self::$_languageCode;
     }
     
     /**
      * Get the current language that is used.
      *
      * @param string $moduleName the module name
      * @return string the language code
      */
     public static function getCurrentUseLanguageCode($moduleName = null) {
     	  $moduleName = is_null($moduleName) ? MvcReg::getModuleName() : $moduleName;
     	  return $_SESSION[$moduleName][self::LANGUAGE_CODE];
     }

}

?>
