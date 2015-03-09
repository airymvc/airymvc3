<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the database access and each kind of the SQL statement.
 *
 * @filesource
 * @package framework\app\library\db\MongoDbAccess
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MongoDbAccess implements DbAccessInterface {
	
	/**
	 * @var array $dbConfigArray
	 */
	protected $dbConfigArray;

	/**
	 * @var object $mongoClient
	 */
	protected $mongoClient;
	
	/**
	 * @var object $database
	 */
	protected $database;
	
	/**
	 * @var array $dbCollection
	 */
	protected $dbCollection;
	
	/**
	 * @var string $selectPart
	 */
	protected $selectPart;
	
	/**
	 * @var string $updatePart
	 */
	protected $updatePart;
	
	/**
	 * @var string $insertPart
	 */
	protected $insertPart;
	
	/**
	 * @var string $wherePart
	 */
	protected $wherePart;
	
	/**
	 * @var string $distinctPart
	 */
	protected $distinctPart;
	
	/**
	 * @var string $orderPart
	 */
	protected $orderPart;
	
	/**
	 * @var string $groupPart
	 */
	protected $groupPart;
	
	/**
	 * @var string $limitPart
	 */
	protected $limitPart;
	
	/**
	 * @var string $offsetPart
	 */
	protected $offsetPart;
	
	/**
	 * @var string $queryType
	 */
	protected $queryType;
	
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
     * 
	 * @example
	 * 
	 * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
	 * 
	 * The connection string always starts with mongodb://, to indicate it is a connection string in this form.
	 * If username and password are specified, the constructor will attempt to authenticate the connection 
	 * with the database before returning. Username and password are optional and must be followed by an @, if specified.
	 * 
     * @param array $config
     */
    public function setComponent($config) {
		$host = $config['host'];
		$userPassword = "{$config['id']}:{$config['pwd']}@";
		$dbStr = $config['database'];
		$connection = "mongodb://{$userPassword}{$host}/";
		$this->setMongoClient($connection);
		$this->database = $this->mongoClient->$dbStr;
    }
    
    //The default MongoClient methods
    
    /**
     * @param string $connection
     */
    public function setMongoClient($connection) {
    	$this->mongoClient = new MongoClient($connection);
    }
    
    /**
     * Get the connection.
     */
    public function getConnections() {
    	return $this->mongoClient->getConnections();
    }
    
    /**
     * Get the hosts.
     */
    public function getHosts() {
    	return $this->mongoClient->getHosts();
    }
    
    /**
     * Get the read preference.
     */
    public function getReadPreference() {
    	return $this->mongoClient->getReadPreference();
    }
    
    /**
     * Get the write concern.
     */
    public function getWriteConcern() {
    	return $this->mongoClient->getWriteConcern();
    }
    
    /**
     * @param object $serverHash
     * @param int $id
     */
    public function killCursor($serverHash, $id) {
    	return $this->mongoClient->killCursor ($serverHash, $id);
    }
    
    public function listDBs() {
    	return $this->mongoClient->listDBs();
    }
    
    public function selectCollection($db, $collection) {
    	return $this->mongoClient->selectCollection($db, $collection);
    }
    
    public function selectDB($name) {
    	return $this->mongoClient->selectDB($name);
    }
    
    public function setReadPreference($read_preference, $tags = NULL) {
    	return $this->mongoClient->setReadPreference ($read_preference, $tags);
    }
    
    public function setWriteConcern($w, $wtimeout= NULL) {
    	return $this->mongoClient->setWriteConcern($w, $wtimeout);
    }
    
    public function __toString() {
    	return $this->mongoClient->__toString();
    }
    //end of default MongoClient methods
    
    public function mongoClient() {
    	return $this->mongoClient;
    }

    /**
     * @param string $collection
     * @param array $columns
     * @param array $criteria
     * @return object Query result.
     */
    public function find($collection, $columns, $criteria) {
    	$dbConnection = $this->database->$collection;
    	return $dbConnection->find($criteria, $columns);
    }
    
    /**
     * This method will return only the first result from the result set
     *  
     * @param string $collection
     * @param array $columns
     * @param array $criteria
     * @return object Query result.
     */
    public function findOne($collection, $columns, $criteria) {
    	$dbConnection = $this->database->$collection;
    	return $dbConnection->findOne($criteria, $columns);
    }
    
    /**
     * @example
     * findAndModify ( array $query [, array $update [, array $fields [, array $options ]]] )
     * 
     * @example
     * array('$set' => array('inprogress' => true, "started" => new MongoDate())), 
     * 						 null, 
     *                       array("sort" => array("priority" => -1), "new" => true)
     *
     * @param string $collection
     * @param array $update
     * @param array $criteria
     * @return object Query result.
     */
    public function findAndModify($collection, $update, $criteria) {
    	$dbConnection = $this->database->$collection;
    	return $dbConnection->findAndModify($criteria, $update);
    }
    
    public function count($column, $collection, $where = array()) {
    	$dbConnection = $this->database->$collection;
    	if (empty($where) && ($column == "*" || $column =="COUNT(*)")) {
    		return $dbConnection->count();
    	} else if (!empty($where) && ($column == "*" || $column =="COUNT(*)")) {
    		return $dbConnection->find($where)->count();
    	} else if (empty($where) && ($column != "*" || $column != "COUNT(*)")) {
    		$critera = array($column => array('$exists' => true));
    		return $dbConnection->find($critera)->count();
    	}  	
    }
    
    /**
     * This select function tries to be compatitive with sql database's
     * "distinct" here is not one to one mapping to sql database
     * SELECT DISTINCT(status) FROM users = db.users.distinct( "status" )
     * 
     * @param mixed $columns
     * @param string $collection : table
     * @param string $distinct
     * @return MongodbComponent
     */
    public function select($columns, $collection, $distinct = null) {
    	$this->queryType = "SELECT";
    	$selectArray = array();
    	if (is_array($columns)) {
    		foreach ($columns as $col) {
    			$selectArray[$col] = 1;
    		}
    		if (!is_null($distinct)) {
    			$this->distinctPart = array("distinct" => "{$collection}", "key" => "{$columns[0]}");
    		}
    	} else {
    		$colElements = explode(",", $columns);
    		foreach ($colElements as $col) {
    			$selectArray[$col] = 1;
    		} 
    		if (!is_null($distinct)) {
    			$this->distinctPart = array("distinct" => "{$collection}", "key" => "{$colElements[0]}");
    		}   		
    	}
    	$this->selectPart = $selectArray;
    	return $this;
    }
    
    /**
     * $condition is an array, it won't be compatitable with old where method.
     * The $condition here must be the array that can be passed in MongoDb
     * Currently, we do not support string for $condition
     * 
     * @param array $condition
     * @return MongodbComponent
     */
    public function where($condition) {
    	if (is_array($condition)) {
    		$this->wherePart = $condition;
    	}
    	//@TODO: do a string operation to convert the string to be a where array
    	
    	return $this;
    }
    
    
    /**
     * @param $collection @string : the name of the table
     * @param $columns @array : the columns array(column_name => column_value, column_name1 => column_value1)
     * @param $where @array: array for insert directly with using execute, 
     * 					     NULL need to call execute
     */
    public function update($columns, $collection, $where = NULL, $options = NULL) {
    	if (!is_null($where)) {
    		$dbConnection = $this->database->$collection;
    		if (is_null($options)) {
    			return $dbConnection->update($where, array('$set' => $columns));
    		}
    		return $dbConnection->update($where, array('$set' => $columns), $options);
    	}
    	//Compatiable part
    	$this->queryType = "UPDATE";
    	$this->dbCollection = $collection;
    	$this->updatePart = array('$set' => $columns);
    	return $this;
    }

	/**
	 * @see DbAccessInterface::delete()
	 * @param string $collection
	 * @param string $where
	 * @param array $options
	 * @return MongoDbAccess
 	 */
    public function delete($collection, $where = NULL, $options = NULL) {
    	if (!is_null($where)) {
    		$dbConnection = $this->database->$collection;
    		if (is_null($options)) {
    			return $dbConnection->remove($where);
    		}
    		return $dbConnection->remove($where, $options);
    	}
    	//Compatiable part
    	$this->queryType = "DELETE";
    	$this->dbCollection = $collection;
    	return $this;
    }
    
    /**
     * @see DbAccessInterface::limit()
     */
    public function limit($offset, $interval) {
    	$this->limitPart = $interval;
    	$this->offsetPart = $offset;
    	return $this;
    }
    
    /**
     * @param array $document: key-value pairs
     * @param string $collection: like table in sql database
     * @param boolean $directly: true for insert directly with using execute, 
     * 							 false need to call execute
     * @return MongoDbAccess
     */
    public function insert($document, $collection, $directly = false, $options = NULL) {
    	if ($directly) {
    		$dbConnection = $this->database->$collection;
    		if (is_null($options)) {
    			return $dbConnection->insert($document);
    		}
    		return $dbConnection->insert($document, $options);
    	}
    	//Compatiable part
    	$this->queryType = "INSERT";
    	$this->dbCollection = $collection;
    	$this->insertPart = $document;
    	return $this;

    }
    
    /**
     * (non-PHPdoc)
     * @see DbAccessInterface::orderBy()
     */
    public function orderBy($column, $ifDesc = NULL) {
    	$this->orderPart = array($column => 1);
    	if ($ifDesc != NULL) {
    		$this->orderPart = array($column => -1);
    	}
    	return $this;
    }
    
    /**
     * @return array
     */
    public function getStatement() {
    	$result = array();
    	//Combine every part of the query statement
    	switch ($this->queryType) {
    		case "SELECT":
    			$result = array("SELECT"     => $this->selectPart,
    							"DATABASE"   => $this->dbConfigArray['database'],
    							"COLLECTION" => $this->dbCollection,
    							"WHERE"      => $this->wherePart,
    							"LIMIT"      => $this->limitPart,
    							"OFFSET"     => $this->offsetPart,
    							"ORDER_BY"   => $this->orderPart,
    							"DISTINCT"   => $this->distinctPart
    						   );
    			break;
    		case "UPDATE":
    			$result = array("UPDATE"     => $this->updatePart,
    							"DATABASE"   => $this->dbConfigArray['database'],
    							"COLLECTION" => $this->dbCollection,
    							"WHERE"      => $this->wherePart
    						   );
    			break;
    		case "INSERT":
    			$result = array("INSERT"     => $this->insertPart,
    							"DATABASE"   => $this->dbConfigArray['database'],
    							"COLLECTION" => $this->dbCollection,
    							"WHERE"      => $this->wherePart
    						   );
    			break;
    		case "DELETE":
    			$result = array("DELETE"     => array(),
    							"DATABASE"   => $this->dbConfigArray['database'],
    							"COLLECTION" => $this->dbCollection,
    							"WHERE"      => $this->wherePart
    						   );
    			break;
    	}
    	return $result;
    }
    
    /**
     * Aggregate each SQL statement part and execute the command
     * @return multitype
     */
    public function execute() {
    	$table = $this->dbCollection;
    	
        switch ($this->queryType) {
            case "SELECT":    	
            	if (empty($this->distinctPart)) {
					$select = $this->database->$table->find($this->wherePart, $this->selectPart);
					if (!empty($this->limitPart)) {
						$select = $select->limit($this->limitPart);
					}
					if (!empty($this->offsetPart)) {
						$select = $select->skip($this->offsetPart);
					}
					if (!empty($this->orderPart)) {
						$select = $select->sort($this->orderPart);
					}
					return $select;
            	} else {
            		return $this->database->$table->command($this->distinctPart);
            	}
                break;
            case "UPDATE":
            	return $this->database->$table->update($where, array('$set' => $columns));
                break;
            case "INSERT":
            	return $this->database->$table->insert($document);
                break;
            case "DELETE":
            	return $this->database->$table->remove($this->wherePart);
                break;
        }
    }
    /**
     * Clean all kinds of the SQL statement
     */
    public function cleanAll(){
    	$this->queryType    = NULL;
    	$this->selectPart   = NULL;
    	$this->wherePart    = NULL;
    	$this->orderPart    = NULL;
    	$this->limitPart    = NULL;
    	$this->offsetPart   = NULL;
    	$this->updatePart   = NULL;
    	$this->insertPart   = NULL;
    	$this->distinctPart = NULL;
    	$this->groupPart    = NULL;
    }

    //unsupport methods
    //TODO: to have a better way to resolve these methods
    
    /**
     * (non-PHPdoc)
     * @see DbAccessInterface::andWhere()
     */
    public function andWhere($opString) {
    	return $this->where($condition);
    }
    /**
     * (non-PHPdoc)
     * @see DbAccessInterface::orWhere()
     */
    public function orWhere($opString) {
    	return $this->where($condition);
    }
    /**
     * (non-PHPdoc)
     * @see DbAccessInterface::inWhere()
     */
    public function inWhere($in) {
    	return $this->where($condition);
    }
    /**
     * (non-PHPdoc)
     * @see DbAccessInterface::innerJoinOn()
     */
    public function innerJoinOn($table, $condition) {
    	return $this;
    }
    /**
     * (non-PHPdoc)
     * @see DbAccessInterface::groupBy()
     */
    public function groupBy($column) {
    	return $this;
    }
    
}