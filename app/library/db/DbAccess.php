<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the database access.
 *
 * @package framework\app\library\db\DbAccess
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class DbAccess extends AbstractAccess implements DbAccessInterface {
	    
	/**
	 * Set the configuration to each database component.
	 *
	 * @see framework\config\config.ini
	 * @see framework\config\example.config.ini
	 * 
	 * @param int $databaseId The database id. This refer to config.ini.
	 * @param string $iniFile The config.ini file path. Default value = NULL
	 */
    public function config($databaseId = 0, $iniFile = NULL) {
    	$config = Config::getInstance();
    	if (!is_null($iniFile)) {
    		$config->setIniFilePath($iniFile);
    	}
    	$configArray = $config->getDBConfig();
    	$this->setDbConfig($configArray[$databaseId]);
    	$this->setComponent($configArray[$databaseId]);
    }

    /**
     * Set the database configurations.
     *
     * @see framework\config\config.ini
     * @see framework\config\example.config.ini
     *
     * @param array $config 
     */
    public function setDbConfig($config) {
    	$this->dbConfigArray = $config;
    }
    
    /**
     * Set the database component object.
     * @param array $config
     */
    public function setComponent($config) {
    	//initialize the object based on the database type
    	$className = ucfirst(strtolower($config['dbtype'])) . 'Component';
    	if (strtolower($config['dbtype']) == "mysql") {
    		$className = ucfirst(strtolower($config['connection_type'])) . 'Component';
    	}
    	
    	$this->_dbComponent = new $className();
    	$this->_dbComponent->setConfig($config);
    }

}

?>
