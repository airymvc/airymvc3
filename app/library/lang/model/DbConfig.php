<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the framework log.
 *
 * @package framework\app\library\log\DbConfig
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class DbConfig {
	
	private static $dbAccessElements = array();

	/**
	 * This is the function to get the database access objects for all the database settings in the config.ini file.
	 * @see framework\config\config.ini
	 * @see framework\config\example.config.ini
	 * @throws AiryException
	 */
    public static function getConfig() {
    	$config = Config::getInstance();
        $dbSettings = $config->getDBConfig();
        
        foreach ($dbSettings as $idx => $configArray) {
			self::assignDbAccess($idx, $configArray);
        }
        
        return self::$dbAccessElements;
    }
    /**
     * 
     * @param int $idx
     * @param array $configArray
     * @throws AiryException
     */
    public static function assignDbAccess($idx, $configArray) {
         if (!isset($configArray['dbtype'])) {
        	 throw new AiryException("no dbtype setting in the config.ini");
         }
         //pdo is the default connection type
         $connectionType = "pdo";
         if (isset($configArray['connection_type'])) {
        	 $connectionType = $configArray['connection_type'];
         } else if (strtolower($configArray['dbtype']) == "mongodb") {
        	 $connectionType = "mongodb";
         }
        	 
         if (strtolower($connectionType) == "pdo") {
         	 $access = new PdoAccess();
         } else if (strtolower($connectionType) == "mongodb") {
         	 $access = new MongoDbAccess();
         } else {
         	 $access = new DbAccess();
         }
         
         //set and put into array
         $access->config($idx);
         self::$dbAccessElements[$idx] = $access;
         
    } 

}
?>
