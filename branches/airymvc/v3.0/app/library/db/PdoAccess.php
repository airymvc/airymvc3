<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the MSSQL database using PDO connection.
 *
 * @filesource
 * @package framework\app\library\db\PdoAccess
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class PdoAccess extends AbstractAccess implements DbAccessInterface  {
    
	/**
	 * @param int $databaseId
	 * @param string $iniFile
	 */
    public function config($databaseId = 0, $iniFile = null) {
    	$config = Config::getInstance();
    	if (!is_null($iniFile)) {
    		$config->setIniFilePath($iniFile);
    	}
    	$configArray = $config->getDBConfig();
    	$this->setDbConfig($configArray[$databaseId]);
    	$this->setComponent($configArray[$databaseId]);
    }

    /**
     * @param array $config
     */
    public function setDbConfig($config) {
    	$this->dbConfigArray = $config;
    }
    
    /**
     * @param array $config
     */
    public function setComponent($config) {
    	//initialize the object based on the database type
    	$className = 'Pdo' . ucfirst(strtolower($config['dbtype'])) . 'Component';
    	$this->_dbComponent = new $className();
    	$this->_dbComponent->configConnection($config);
    }
    
    /**
     * Call PDO::prepare()
     * @param string $statement
     * @param array $driverOptions
     * @return object
     */
    public function prepare($statement, array $driverOptions = array()) {
    	//return a prepareStatement here
    	return $this->_dbComponent->prepare($statement, $driverOptions);
    }
    
    /**
     * Call PDO::beginTransaction()
     * @return PdoAccess
     */
    public function beginTransaction() {
    	$this->_dbComponent->beginTransaction();
    	return $this;
    }
    
    /**
     * Call PDO::rollBack() to roll back the transaction
     */
    public function rollBack() {
    	$this->pdoConn->rollBack();
    }
    
    /**
     * @return PdoAccess
     */
    public function commit() {
    	$this->_dbComponent->commit();
    	return $this;
    }
    
    /**
     * Call PDO::exec()
     * @param string $statement
     * @return PdoAccess
     */
    public function exec($statement = null) {
    	$this->_dbComponent->exec($statement);
    	return $this;
    }
    
    /**
     * @param int $attribute
     * @param mixed $value
     * @return PdoAccess
     */
    public function setAttribute($attribute, $value) {
    	$this->_dbComponent->setAttribute($attribute, $value);
    	return $this;
    }
    
    /**
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute($attribute) {
    	return $this->_dbComponent->getAttribute($attribute);
    }
    
    /**
     * Call PDO::errorCode()
     */
    public function errorCode() {
    	return $this->_dbComponent->errorCode();
    }
    
    /**
     * Call PDO::errorInfo()
     */    
    public function errorInfo() {
    	return $this->_dbComponent->errorInfo();
    }

    /**
     * Call PDO::getAvailableDrivers()
     */
    public function getAvailableDrivers() {
    	return $this->_dbComponent->getAvailableDrivers();
    }
    
    /**
     * Call PDO::inTransaction()
     */
    public function inTransaction() {
    	return $this->_dbComponent->inTransaction();
    }
    
    /**
     * @param string $name
     */
    public function lastInsertId($name = NULL) {
    	return $this->_dbComponent->lastInsertId($name = NULL);
    }
    
    /**
     * @param string $statement
     * @param string $fetchType
     * @param string $fetch
     * @param array $ctorargs
     */
    public function query($statement, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
    	return $this->_dbComponent->query($statement, $fetchType, $fetch, $ctorargs);
    }
    
    /**
     * @param string $str
     * @param int $parameterType Default value = PDO::PARAM_STR
     */
    public function quote($str, $parameterType = PDO::PARAM_STR) {
    	return $this->_dbComponent->quote($str, $parameterType);
    }
    
    /**
     * @see AbstractAccess::execute()
     */
    public function execute($statement = NULL, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
    	return $this->_dbComponent->execute($statement, $fetchType, $fetch, $ctorargs);
    }
    
    /**
     * Call setAutoClose()
     * @param object $value
     */
    public function setAutoClose($value) {
    	return $this->_dbComponent->setAutoClose($value);
    }
    
    /**
     * Call closeConnection()
     */
    public function closeConnection() {
    	return $this->_dbComponent->closeConnection();
    }
    
    /**
     * Call setConnection()
     * @param string $dsn
     * @param string $userid
     * @param string $passwd
     */
    public function setConnection($dsn = NULL, $userid = NULL, $passwd = NULL) {	
    	return $this->_dbComponent->setConnection();
    }

 
}

?>
