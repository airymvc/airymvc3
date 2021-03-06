<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\app\lib\db;

require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'db' .DIRECTORY_SEPARATOR . 'DbInterface.php';
use airymvc\app\lib\db\DbInterface;

/**
 * This handles the database access and each kind of the SQL statement.
 *
 * @filesource
 * @package airymvc\app\lib\db\MongoDb
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MongoDbV7 implements DbInterface {
	
	
	const MONGO_CURSOR           = "mongo_cursor";
	const BOTH_INDEX_KEY_STRING	 = "both_index_key_string";
	const KEY_STRING	         = "key_string";
	const INDEX	                 = "index";
	
	/**
	 * @var array $dbConfig
	 */
	protected $dbConfig;

	/**
	 * @var object $mongoClient
	 */
	protected $mongoClient;
	
	/**
	 * @var string $database
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
	 * The last insert Id
	 * @var string $lastInsertId
	 */
	
	protected $lastInsertId;
	
	/**
	 * Determine if adding _idString during insert. If NOT, query result will add _idString.
	 * @var string $hasIdString
	 */
	protected $hasIdString;
	
	/**
	 * @param string $key
	 * @return string|array
	 */
    public function dbConfig($key = NULL) {
    	if (is_null($key)) {
    		return $this->dbConfig;
    	}
    	if (!isset($this->dbConfig[$key])) {
    		return NULL;
    	}
    	return $this->dbConfig[$key];
    }
    
    /**
     * @param array $dbConfig
     */
    public function setDbConfig($dbConfig) {
    	$this->dbConfig = $dbConfig;
    }
    
    /**
     * Set the database component object.
     * @param array $dbConfig
     */
    public function initDb($dbConfig = NULL) {
    	if (!is_null($dbConfig)) {
    		$this->setDbConfig($dbConfig);
    	}
    	if (is_null($this->dbConfig)) {
    		echo "ERROR: No database configuration is set!!";
    		//@throw an exception.
    	}

    	$this->setDbConfig($this->dbConfig);
    	$this->setConnection();
    	if (isset($this->dbConfig['%insert_idstring'])) {
    		if ($this->dbConfig['%insert_idstring'] == 'true' || $this->dbConfig['%insert_idstring'] == true || $this->dbConfig['%insert_idstring'] == 1) {
    			$this->hasIdString = true;
    		} else {
    			$this->hasIdString = false;
    		}
    	} else {
    		//default is false;
    		$this->hasIdString = false;
    	}
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
     * @param string $dsn
     * @param string $userid
     * @param string $passwd
     */
    public function setConnection ($host = NULL, $userid = NULL, $passwd = NULL) {
    	
		$host = !is_null($host) ? $host : $this->dbConfig['%host'];
		$userid = !is_null($userid) ? $userid : $this->dbConfig['%id'];
		$passwd = !is_null($passwd) ? $passwd : $this->dbConfig['%pwd'];	
		$database = $this->dbConfig['%database'];
		$userPassword = "{$userid}:{$passwd}@";

		$this->mongoClient = new \MongoDB\Driver\Manager("mongodb://{$userPassword}{$host}");

		$this->database = $this->selectDB($database);
    }

    
    //The default MongoClient methods
        
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
    	$listdatabases = new \MongoDB\Driver\Command(["listDatabases" => 1]);
		$result = $this->mongoClient->executeCommand("admin", $listdatabases);
    	
    	$databases = current($result->toArray());
    	return $databases;
    }
    
    public function selectCollection($db, $collection) {
    	return $this->mongoClient->selectCollection($db, $collection);
    }
    
    public function selectDB ($name = NULL) {
    	if (is_null($name)) {
    		$name = $this->dbConfig["%database"];
    	}

    	return $name;
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
    	$this->dbCollection = $collection;
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
    		if (isset($condition['_id'])) {
    			$condition['_id'] = new \MongoDB\BSON\ObjectId($condition['_id']);
    		}
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
    	if (is_null($where)) {
			error_log("Missing where part. No actually update.");
			return;	
    	} else {
    		$namespace = $this->database . "." . $collection;
    		$bulk = new \MongoDB\Driver\BulkWrite;
    		$bulk->update($where, array('$set' => $columns));
    		return $this->mongoClient->executeBulkWrite($namespace, $bulk);
    	}
    	
    	//Compatiable part
    	$this->queryType = "UPDATE";
    	$this->dbCollection = $collection;
    	$this->updatePart = array('$set' => $columns);
    	$this->wherePart = $where;
    	
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
    	if (is_null($where)) {
			error_log("WARNING: Do not pass the WHERE part in delete(). Need to make sure calling where() later!");
    	} else {
    		$namespace = $this->database . "." . $collection;
            $bulk = new \MongoDB\Driver\BulkWrite;
            $bulk->delete($where);
            return $this->mongoClient->executeBulkWrite($namespace, $bulk);
    	}
    	//Compatiable part
    	$this->queryType = "DELETE";
    	$this->dbCollection = $collection;
    	$this->wherePart = $where;
    	return $this;
    }
    
    /**
     * @see DbAccessInterface::limit()
     */
    public function limit($offset, $interval) {
    	$this->limitPart = $interval;
    	//@TODO: Need to figure out offset
    	//$this->offsetPart = $offset;
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
    		$namespace = $this->database . "." . $collection;
    		$bulk = new \MongoDB\Driver\BulkWrite;
    		$mongoDbObjectId = new \MongoDB\BSON\ObjectID;
    		$document['_id'] = $mongoDbObjectId;
    		if ($this->hasIdString) {
    			$document['_idString'] = $mongoDbObjectId->__toString();
    		}
    		$bulk->insert($document);
    		$this->lastInsertId = $document['_id']->__toString();
    		$this->mongoClient->executeBulkWrite($namespace, $bulk);
    		return $this->lastInsertId;
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
    public function execute($option = self::INDEX) {
        $useFineOne = FALSE;
    	$table = $this->dbCollection;
    	$namespace = $this->database.".".$this->dbCollection;
    	
        switch ($this->queryType) {
            case "SELECT":
            	$queryResult = NULL;
            	$queryOptions = array();
            	$query = new \MongoDB\Driver\Query([]);
            	//@NOTE: When querying with _id, means the Mongo ObjectId will be used. Need to call fineOne.
            	if (isset($this->wherePart['_id'])) {
            		$useFineOne = TRUE;
            	}
            	if (!empty($this->orderPart)) {
            		$queryOptions['sort'] = $this->orderPart;
            	}
            	if (!empty($this->limitPart)) {
            		$queryOptions['limit'] = $this->limitPart;
            	}
            	if (!empty($this->selectPart)) {
            		$queryOptions['projection'] =  $this->selectPart;
            	}
            	if (!empty($this->wherePart)) {
            		$query = new \MongoDB\Driver\Query($this->wherePart, $queryOptions);
            	} 

                try { 	
            		$queryResult = $this->mongoClient->executeQuery($namespace, $query);
            	} catch (Exception $e) {
            		echo $e->getMessage();
            	}
            	
            	//if using cursor, directly return the query result
            	if ($option == self::MONGO_CURSOR) {
            		return $queryResult;
            	}
            	
            	$returnArray = NULL;
            	if (!empty($queryResult)) {
            		$returnArray = $this->objToArray($queryResult);
            	}
            	return $returnArray;
                break;
            case "UPDATE":
            	$bulk = new \MongoDB\Driver\BulkWrite;
            	$bulk->update($this->wherePart, $this->updatePart);
            	return $this->mongoClient->executeBulkWrite($namespace, $bulk);
                break;
            case "INSERT":
            	$bulk = new \MongoDB\Driver\BulkWrite;
            	$doc = $this->insertPart;
            	$doc['_id'] = new \MongoDB\BSON\ObjectID;
            	if ($this->hasIdString) {
            		$doc['_idString'] = $doc['_id']->__toString();
            	}
            	$bulk->insert($doc);
            	$this->lastInsertId = $doc['_id']->__toString();
            	$this->mongoClient->executeBulkWrite($namespace, $bulk);
            	return $this->lastInsertId;
                break;
            case "DELETE":
            	$bulk = new \MongoDB\Driver\BulkWrite;
            	$bulk->delete($this->wherePart);
            	return $this->mongoClient->executeBulkWrite($namespace, $bulk);
                break;
        }
    }
    /**
     * Clean all kinds of the SQL statement
     */
    public function cleanAll(){
    	$this->queryType    = NULL;
    	$this->selectPart   = NULL;
    	$this->wherePart    = array();
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
    	return $this->where($opString);
    }
    /**
     * (non-PHPdoc)
     * @see DbAccessInterface::orWhere()
     */
    public function orWhere($opString) {
    	return $this->where($opString);
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
    
    /**
     * Get the last insert id 
     * @return string
     */
    public function lastInsertId() {
    	return $this->lastInsertId;
    }
    
    protected function objToArray($objArray) {
    	$result = array();
    	foreach ($objArray as $idx => $stdObj) {
    		if (is_object($stdObj)) {
    			// Gets the properties of the given object
    			// with get_object_vars function
    			$props = get_object_vars($stdObj);
    		}
    		$idObj = $props["_id"];
    		unset($props["_id"]);
    		$convertedArray = json_decode(json_encode($props), TRUE);
    		$convertedArray["_id"] = $idObj;
    		if (!$this->hasIdString) {
    			$convertedArray["_idString"] = $idObj->__toString();
    		}
    		$result[$idx] = $convertedArray;
    	}
    	
    	return $result;
    }
    
}