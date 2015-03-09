<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the database using PDO connection.
 *
 * @filesource
 * @package framework\app\library\db\PdoSqlComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class PdoSqlComponent extends SqlComponent {
	
	/**
	 * @var object $pdoConn
	 */
	protected $pdoConn;
	
	/**
	 * @var string $dsn
	 */
	protected $dsn;
	
	/**
	 * @var string $host
	 */
	protected $host;
	
	/**
	 * @var int $port
	 */
	protected $port;
	
	/**
	 * @var boolean $autoConnectionClose
	 */
	protected $autoConnectionClose = false;
 	
	/**
	 * Set the identifiers in the statement
	 */
    protected function setIdentifier() {
    	// in order to fit pdo's prepare statement, take out the identifiers
    	// ex: :field1
    	$this->setOpenIdentifier("");
    	$this->setCloseIdentifier("");
    }
    
    /**
     * @return boolean
     */
    public function beginTransaction() {
    	return $this->pdoConn->beginTransaction();
    }
    
    /**
     * @param string $statement
     * @param array $driverOptions
     * @return PDOStatement
     */
    public function prepare($statement, array $driverOptions = array()) {
    	//return a prepareStatement here
    	return $this->pdoConn->prepare($statement, $driverOptions);
    }
    
    /**
     * PDO::rollBack()
     */
    public function rollBack() {
    	$this->pdoConn->rollBack();
    }
    
    /**
     * @return boolean
     */
    public function commit() {
    	return $this->pdoConn->commit();
    }
    
    /**
     * @param string $statement
     * @return int
     */
    public function exec($statement = null) {
    	$statement = is_null($statement) ? $this->getStatement() : $statement;
    	return $this->pdoConn->exec($statement);
    }
    
    /**
     * @param int $attribute
     * @param mixed $value
     */
    public function setAttribute($attribute, $value) {
    	$this->pdoConn->setAttribute($attribute, $value);
    }

    /**
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute($attribute) {
    	return $this->pdoConn->getAttribute($attribute);
    }
    
    /**
     * @return mixed
     */
    public function errorCode() {
    	return $this->pdoConn->errorCode();
    }
    
    /**
     * @return array
     */
    public function errorInfo() {
    	return $this->pdoConn->errorInfo();
    }
    
    /**
     * @return array
     */
    public function getAvailableDrivers() {
    	return $this->pdoConn->getAvailableDrivers();
    }
    
    /**
     * @return boolean
     */
    public function inTransaction() {
    	return $this->pdoConn->inTransaction();
    }
    
    /**
     * @param string $name
     * @return string
     */
    public function lastInsertId($name = NULL) {
    	return $this->pdoConn->lastInsertId($name = NULL);
    }
    
    /**
     * @param string $statement
     * @param string $fetchType
     * @param string $fetch
     * @param array $ctorargs
     * @return PDOStatement
     */
    public function query($statement, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
    	if (!is_null($fetchType)) {
    		if (is_int($fetch)) {
    			return $this->pdoConn->query($statement, $fetchType, $fetch);
    		}
    		if (is_string($fetch)) {
    			return $this->pdoConn->query($statement, $fetchType, $fetch, $ctorargs);
    		}
    		if (is_object($fetch)) {
    			return $this->pdoConn->query($statement, $fetchType, $fetch);
    		}
    	}
    	return $this->pdoConn->query($statement);
    }
    
    /**
     * @param string $str
     * @param int $parameterType Default value = PDO::PARAM_STR
     * @return string
     */
    public function quote($str, $parameterType = PDO::PARAM_STR) {
    	return $this->pdoConn->quote($str, $parameterType);
    }
    
    /**
     * @see SqlComponent::execute()
     */ 
    public function execute($statement = NULL, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {

    	$statement = is_null($statement) ? $this->getStatement() : $statement;
    	$results = null;
		try {
			 if (is_null($this->pdoConn)) {
				 $this->setConnection();
			 }
			 $results = $this->query($statement, $fetchType, $fetch, $ctorargs);
		} catch(PDOException $e) {
    		 echo 'PDO ERROR: ' . $e->getMessage();
		}
		//close the connection
		if ($this->autoConnectionClose) {
			$this->closeConnection();
		}
        $this->cleanAll();
        
        return $results;
    }
    
    /**
     * @param string $value
     * @return PdoSqlComponent
     */
    public function setAutoClose($value) {
    	$this->autoConnectionClose = $value;
    	return $this;
    }
    
    /**
     * @return PdoSqlComponent
     */
    public function closeConnection() {
    	$this->pdoConn = null;
    	return $this;
    }

    /**
     * @param string $dsn
     * @param string $userid
     * @param string $passwd
     * @return PdoSqlComponent
     */
    public function setConnection($dsn = NULL, $userid = NULL, $passwd = NULL) {
    	$dsn = is_null($dsn) ? $this->dsn : $dsn;
    	$userid = is_null($userid) ? $this->dbConfigArray['id'] : $userid;
    	$passwd = is_null($passwd) ? $this->dbConfigArray['pwd'] : $passwd;
    	$this->pdoConn = new PDO($dsn, $userid, $passwd);	
    	return $this;
    }
    
    /**
     * @see SqlComponent::sqlEscape()
     */
    function sqlEscape($content) {

        //check if $content is an array
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = $this->quote($value);
            }
        } else {
            //check if $content is not an array
            $content = $this->quote($content);
        }

        return $content;
    }    
}

?>
