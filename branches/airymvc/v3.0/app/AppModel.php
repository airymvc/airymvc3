<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */


/**
 * This is the controller class that is used for intializing the instance and set variables.
 *
 * @package framework\app\AppModel
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AppModel extends AbstractModel {

    /**
     * To deal with the database config(s)
     * 
     * @return object database 
     */
    public function initialDB() {
        $this->multiDb = DbConfig::getConfig();
        $this->db = $this->multiDb[0];
        return $this->db;
    }

	/**
	 * Sets the database config variables
	 * 
 	 * @param array $config
 	 */
    public function setDb($config) {
    	$this->multiDb = DbConfig::getConfig();
    	$this->db = DbConfig::assignDbAccess(0, $config);
    }

    
	/**
	 * Sets multiple databases configs.
 	 * 
 	 * @param int $databaseId
 	 * @param array $config
 	 */
    public function setMultiDb($databaseId, $config) {
    	$this->multiDb = DbConfig::getConfig();
    	$this->db = DbConfig::assignDbAccess($databaseId, $config);
    }
    
}

?>
