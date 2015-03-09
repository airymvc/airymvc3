<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * This utility class saves objects that initially are used.
 *
 * @package framework\core\Loader
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Loader
{
  private static $loaded = array();
  
  /**
   * This method loads the objects that are used initially.
   *
   * @return array the objects
   */
  public static function load($object) {
    $valid = array(  "Ini",
                     "Initializer",
                     "MvcReg",
                     "PathService",
                     "Router",
                     "Dispatcher",
                     "Storage"
                  );
    			  
    if (!in_array($object, $valid)){
        throw new Exception("Not a valid object '{$object}' to load");
    }
    if (empty(self::$loaded[$object])){
        self::$loaded[$object]= new $object();
    }
    return self::$loaded[$object];
  }
}
?>
