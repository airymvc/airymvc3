<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
 
namespace airymvc\app\lib\db;

require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Config.php';
use airymvc\core\Config;

 
/**
 * This abstract class is used for composing each kind of the SQL statement.
 *
 * @filesource
 * @package framework\app\library\db\SqlComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class SqlComponent {

	/**
	 * @var array $dbConfig
	 */
    protected $dbConfig;
    
    /**
     * @var string $queryStmt
     */
    protected $queryStmt;
    
    /**
     * 
     * @var string $selectStatement
     */
    protected $selectStatement;
    
    /**
     * @var string $selectPart
     */
    protected $selectPart;
    
    /**
     * @var string $updatePart
     */
    protected $updatePart;
    
    /**
     * @var string $deletePart
     */
    protected $deletePart;
    
    /**
     * @var string $insertPart
     */
    protected $insertPart;
    
    /**
     * @var array $joinOnParts
     */
    protected $joinOnParts = array(); 

    /**
     * @var string $wherePart
     */
    protected $wherePart;
    
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
     * @var array $keywords
     */
    protected $keywords = array('CURRENT_TIMESTAMP' => "CURRENT_TIMESTAMP");
    
    /**
     * @var string $queryType
     */
    protected $queryType;

    /**
     * @var string $joinPart
     */
    protected $joinPart;
    
    /**
     * @var string $joinOnPart
     */
    protected $joinOnPart;
    
    /**
     * @var string $openIdentifier
     */
    protected $openIdentifier  = "";
    
    /**
     * @var string $closeIdentifier
     */
    protected $closeIdentifier = "";
    
    
    
    // POD part
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
    
    //End of PDO part

       
    /**
     * @param array $configArray
     */
    public function setDbConfig($dbConfig) {
    	$this->dbConfig = $dbConfig;
    }

    /**
     * @example
     * array (op of 'AND' or 'OR', array (op of 'like' or '=', array of (column => value)))
     * EX: array("AND"=>array("="=>array(field1=>value1, field2=>value2), ">"=>array(field3=>value3)))
     *     array(""=>array("="=>array(field1=>value1)))
     * if operators is null, all operators are "AND"
     * 
     * if it is after a inner join, should use "table.field1=>value1"
     * 
     * @param array|string $condition
     * @return SqlComponent
     */
    public function where($condition) {

    	$this->wherePart = " WHERE ";
        if (is_array($condition)) {
        	$this->wherePart .= $this->composeWhereByArray($condition);
        } else {
        	$this->wherePart .= $this->composeWhereByString($condition);
        }
        return $this;

    }
     /**
      * @param string $condition
      * @return string
      */
    protected function composeWhereByString($condition) {
    	$condition = $this->sqlEscape($condition);
    	return "({$condition})";
    }
    /**
     * @param array $condition
     * @throws AiryException
     * @return string
     */
    protected function composeWhereByArray($condition) {
    	$wherePart = "";
        $ops = array_keys($condition);
        if (empty($ops[0])) {
            //NO "AND", "OR" 
            $keys = array_keys($condition[$ops[0]]);
            $opr = $keys[0];
            $fieldArray = $condition[$ops[0]][$opr];
            $sub_keys = array_keys($fieldArray);
            
            $wherePart = $this->attachWhere($wherePart, $sub_keys[0], $fieldArray, $opr);
            
        } else {   
        	//Multiple Join Conditions
            $firstOne = TRUE;
            foreach ($ops as $index => $op) {
                foreach ($condition[$op] as $mopr => $fv_pair) {
                    if (is_array($fv_pair)) {
                        $mkeys = array_keys($fv_pair);
                        foreach ($mkeys as $idx => $mfield) {
                            if ($firstOne) {
                            	$oprator = null;
                                $firstOne = FALSE;
                            } else {
                            	$oprator = $op;
                            }
                            $wherePart = $this->attachWhere($wherePart, $mfield, $fv_pair, $mopr, $oprator);
                        }
                    } else {
                        //@TODO: to consider if the error log is necessary here
                        //log the error
                        $message = "JOIN condition uses array but not a correct array";
                        throw new AiryException($message, 0);
                    }
                }
            }
        }
        return $wherePart;    	
    }
    
    /**
     * 
     * @param string $whereString
     * @param string $fieldKey
     * @param array $fieldArray
     * @param string $relationalOperator
     * @param string $operator
     * @return string
     */
    protected function attachWhere($whereString, $fieldKey, $fieldArray, $relationalOperator, $operator = null) {
        $pos = strpos($fieldKey, '.');
        $operator = is_null($operator) ? "" : strtoupper($operator);
        $key = "{$this->openIdentifier}{$fieldKey}{$this->closeIdentifier}";
        //Determine if it is a SQL function
        if (strpos($fieldKey, ")") > 1) {
        	$key = "$fieldKey";
        }
        if ($pos != false){
            $tf = explode (".", $fieldKey);
            $key = "{$this->openIdentifier}{$tf[0]}{$this->closeIdentifier}.{$this->openIdentifier}{$tf[1]}{$this->closeIdentifier}";
            if (strpos($fieldKey, ")") > 1) {
            	$key = "{$tf[0]}.{$tf[1]}";
            }
        }
        $whereString .= "{$operator} {$key} {$relationalOperator} '{$fieldArray[$fieldKey]}' ";
        return $whereString;    	
    }
    
    /**
     * @param string $opString
     * @return SqlComponent
     */
    public function andWhere($opString) {
    	$opString = $this->sqlEscape($opString);
    	$opString = " AND ({$opString})";
    	$this->wherePart .= $opString; 
    	return $this;  	
    }
    /**
     * @param string $opString
     * @return SqlComponent
     */
    public function orWhere($opString) {
    	$opString = $this->sqlEscape($opString);
    	$opString = " OR ({$opString})";
    	$this->wherePart .= $opString; 
    	return $this;    	
    }
	/**
	 * @param string $in
	 * @return SqlComponent 
	 */
    public function inWhere($in) {
    	$opString = " IN ({$in})";
    	$this->wherePart .= $opString; 
    	return $this;    	
    }

    /**
     * @example innerJoinOn ("tableName", "tableName.Key = to_be_Join_tableName.Key") 
     * 
     * @param string $table
     * @param string $condition
     */
    public function innerJoinOn($table, $condition) {

		$joinOn = "INNER JOIN {$this->openIdentifier}{$table}{$this->closeIdentifier} ON {$condition}";
		$this->joinOnParts[] = $joinOn;
        return $this;
    }
    
    /**
     * Get JOIN ON part text
     * @return string
     */
    public function getJoinOn() {
    	$joinOnString = "";
    	foreach ($this->joinOnParts as $i => $joinOn) {
    		$joinOnString = $joinOnString . " " . $joinOn;
    	}
    	return $joinOnString;
    }

    /**
     * Compose the SELECT part of the SQL query.
     * @param array $columns
     * @param string $table
     * @param string $distinct
     * @return object The instance itself.
     */
    public function select($columns, $table, $distinct = null) {
        $this->queryType = "SELECT";
        if (is_null($distinct)) {
            $selectString = 'SELECT ';
        } else {
            $selectString = 'SELECT DISTINCT ';         
        }
        
        if (is_array($columns)) {
        	$this->selectPart = $this->composeSelectByArray($selectString, $columns, $table);
        } else {
        	$this->selectPart = $this->composeSelectByString($selectString, $columns, $table);
        }
        
        return $this;
    }
    
    /**
     * 
     * @param string $selectString
     * @param array $columns
     * @param string $table
     * @return string
     */
    protected function composeSelectByArray($selectString, $columns, $table) {
    	$selectPart = $selectString;
        foreach ($columns as $index => $col) {
            if ($index == count($columns) - 1) {
                $selectPart .= $col . " FROM {$this->openIdentifier}" . $table . "{$this->closeIdentifier}";
            } else {
                $selectPart .= $col . ", ";
            }
        }  
        return $selectPart;  	
    }
    
    /**
     *
     * @param string $selectString
     * @param array $columns
     * @param string $table
     * @return string
     */    
    protected function composeSelectByString($selectString, $columnString, $table) {
    	$selectPart = $selectString . $columnString ." FROM {$this->openIdentifier}" . $table . "{$this->closeIdentifier}";
    	return $selectPart;
    }


    /**
     * Compose the DELETE SQL query.
     * @param string $table The name of the table.
     * @param array $columns The columns array(column_name => column_value, column_name1 => column_value1)
     * @return SqlComponent
     */
    public function update($columns, $table) {
        $this->queryType = "UPDATE";
        $this->updatePart = "UPDATE {$this->openIdentifier}" . $table . "{$this->closeIdentifier} SET ";
        foreach ($columns as $column_index => $column_value) {
        	$lastAppend = "', ";
        	if (strpos($column_value, ")") > 1) {
        		$lastAppend = ", ";
        	}
            $updateElement = "{$this->openIdentifier}" . $column_index . "{$this->closeIdentifier}='" . $column_value . $lastAppend;
            if (strpos($column_value, ")") > 1) {
           		$updateElement = "{$this->openIdentifier}" . $column_index . "{$this->closeIdentifier}=" . $column_value . $lastAppend;
            }
            $this->updatePart .= $updateElement;
        }        
        $this->updatePart = rtrim($this->updatePart, ", ");

        return $this;
    }

    /**

     * @param array $columns The columns array(column_name => column_value, column_name1 => column_value1) $keywords like TIMESTAMP, it needs to be taken care of 
     * @param string $table The name of the table
     * @return SqlComponent
     */
    public function insert($columns, $table) {
        $this->queryType = "INSERT";
        $this->insertPart = "INSERT INTO " . $table . " ( ";
        $size = count($columns) - 1;
        $n = 0;
        foreach ($columns as $columnIndex => $columnValue) {
        	$attach = "{$this->closeIdentifier}, ";
            if ($n == $size) {
            	$attach = "{$this->closeIdentifier}) VALUES (";
            }
            $this->insertPart = $this->insertPart . "{$this->openIdentifier}" . $columnIndex . $attach;
            $n++;
        }

        $n = 0;
        foreach ($columns as $columnIndex => $columnValue) {
        	$middle = "'";
            $last = "', ";
            if ($n == $size) {
            	$middle = "'";
            	$last = "')";
            }
            if (array_key_exists($columnValue, $this->keywords)) {
            	$middle = "";
            	$last = "";
            }
            $this->insertPart = $this->insertPart . $middle . $columnValue . $last;
            $n++;
        }

        return $this;
    }

    /**
     * Compose the DELETE SQL query.
     * @param string $table
     * @return SqlComponent
     */
    public function delete($table) {
        $table = $this->sqlEscape($table);
        $this->queryType = "DELETE";
        $this->deletePart = "DELETE FROM " . $table;
        return $this;
    }

    /**
     * @param int $offset 
     * @param int $interval
     * @return SqlComponent
     */
    public function limit($offset, $interval) {

    	$this->limitPart = "";    	
    	if (is_null($offset) && is_null($interval)) {
    		return $this;
    	}

        $offset = (!is_null($offset)) ? $offset : 0;

        $insert = "";
        if (!is_null($offset)) {
        	$insert = trim($offset);         
        }
        $this->limitPart = " LIMIT " . $insert . ", " . trim($interval);
        return $this;
    }

    /**
     * @param string $column Column name in the database.
     * @param int $if_desc NULL or 1.
     * @return SqlComponent
     */
    public function orderBy($column, $ifDesc = NULL) {
    	$this->orderPart = "";
        $desc = "";
        if ($ifDesc != NULL) {
        	$desc = " DESC";
        }
        $this->orderPart .= " ORDER BY " . $column . $desc;
        return $this;
    }
    
    /**
     * @param string $column Column name in the database.
     * @return SqlComponent
     */
    public function groupBy($column) {
    	$this->groupPart = "";
        $this->groupPart = " GROUP BY " . $column;
        return $this;
    }
    

    /**
     * @return array $dbConfig
     */
    public function getdbConfig() {
        return $this->dbConfig;
    }

    /**
     * @return string $queryStmt
     */
    public function getStatement() {
        //Combine every part of the query statement
        switch ($this->queryType) {
            case "SELECT":
                $this->queryStmt = null;
				$this->queryStmt = $this->composeSelectStatement($this->selectPart, $this->getJoinOn(), $this->joinPart, $this->joinOnPart, $this->wherePart, 
									 							 $this->groupPart, $this->orderPart, $this->limitPart);
                break;
            case "UPDATE":
                $this->queryStmt = null;
                $this->queryStmt = $this->updatePart . $this->wherePart;
                break;
            case "INSERT":
                $this->queryStmt = null;
                $this->queryStmt = $this->insertPart;
                break;
            case "DELETE":
                $this->queryStmt = null;
                $this->queryStmt = $this->deletePart . $this->wherePart;
                break;
        }
        return $this->queryStmt;
    }
    
    /**
     * if the query type is select, this function is to compose the statement
     * 
     * @param string $selectPart
     * @param string $joinPart
     * @param string $joinOnPart
     * @param string $wherePart
     * @param string $groupPart
     * @param string $orderPart
     * @param string $limitPart
     */
    public function composeSelectStatement($selectPart, $joinOnParts, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart) {
        $queryStmt = $selectPart . $joinOnParts . $joinPart . $joinOnPart . $wherePart . $groupPart . $orderPart . $limitPart;
        return $queryStmt;
    }    
 
   /**
 	* 
 	* @deprecated Not suggested to use.
 	*/
    public function getSelectStatement(){
        if ($this->queryType != "SELECT") {
            return null;
        }
        $this->selectStatement = null;
        $this->selectStatement = $this->selectPart . $this->getJoinOn() . $this->joinPart . $this->joinOnPart
                           . $this->wherePart . $this->groupPart . $this->orderPart . $this->limitPart;         
        return $this->selectStatement;
    }
    /**
     * Clean all the query.
     */
    public function cleanAll(){
        $this->queryType  = "";
        $this->selectPart = "";
        $this->joinOnParts = array();
        $this->joinPart   = "";
        $this->joinOnPart = "";
        $this->wherePart  = "";
        $this->orderPart  = "";
        $this->limitPart  = "";
        $this->updatePart = "";
        $this->insertPart = "";
        $this->deletePart = "";
        $this->groupPart  = "";
    }



    /**
     * @param string $queryStmt
     */
    public function setStatement($queryStmt) {
        $this->queryStmt = $queryStmt;
    }

    
    //The following getter is for unit tests
    
	/**
	 * @return string $selectPart
	 */
	public function getSelectPart() {
		return $this->selectPart;
	}

	/**
	 * @return string $updatePart
	 */
	public function getUpdatePart() {
		return $this->updatePart;
	}

	/**
	 * @return string $deletePart
	 */
	public function getDeletePart() {
		return $this->deletePart;
	}

	/**
	 * @return string $insertPart
	 */
	public function getInsertPart() {
		return $this->insertPart;
	}

	/**
	 * @return string $joinPart
	 */
	public function getJoinPart() {
		return $this->joinPart;
	}

	/**
	 * @return string $joinOnPart
	 */
	public function getJoinOnPart() {
		return $this->joinOnPart;
	}

	/**
	 * @return string $wherePart
	 */
	public function getWherePart() {
		return $this->wherePart;
	}

	/**
	 * @return string $orderPart
	 */
	public function getOrderPart() {
		return $this->orderPart;
	}

	/**
	 * @return string $groupPart
	 */
	public function getGroupPart() {
		return $this->groupPart;
	}

	/**
	 * @return string $limitPart
	 */
	public function getLimitPart() {
		return $this->limitPart;
	}
	/**
	 * @return string $closeIdentifier
	 */
	public function getCloseIdentifier() {
		return $this->closeIdentifier;
	}

	/**
	 * @param string $closeIdentifier
	 */
	public function setCloseIdentifier($identifier) {
		$this->closeIdentifier = $identifier;
	}
	/**
	 * @return string $openIdentifier
	 */
	public function getOpenIdentifier() {
		return $this->openIdentifier;
	}

	/**
	 * @param string $openIdentifier
	 */
	public function setOpenIdentifier($identifier) {
		$this->openIdentifier = $identifier;
	}
	

	//PDO part:
	
	protected function setDSN() {}
	
	protected function getDSN() {
		return $this->dsn;
	}
	
	
// 	/**
// 	 * Set the identifiers in the statement
// 	 */
// 	protected function setIdentifier() {
// 		// in order to fit pdo's prepare statement, take out the identifiers
// 		// ex: :field1
// 		$this->setOpenIdentifier("");
// 		$this->setCloseIdentifier("");
// 	}
	
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
	 * @return MysqlComponent|MssqlComponent
	 */
	public function setConnection($dsn = NULL, $userid = NULL, $passwd = NULL) {
		if (is_null($dsn)) {
			$this->setDSN();
			$dsn = $this->dsn;
		}
		$userid = is_null($userid) ? $this->dbConfig['%id'] : $userid;
		$passwd = is_null($passwd) ? $this->dbConfig['%pwd'] : $passwd;
		$this->pdoConn = new \PDO($dsn, $userid, $passwd);
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
	
	// End of PDO parts
	
}

?>
