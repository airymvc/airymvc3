<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
 
namespace airymvc\app\lib\db;

use airymvc\app\lib\db\SqlComponent;

/**
 * This handles the MSSQL database using PDO connection.
 *
 * @filesource
 * @package framework\app\library\db\PdoMssqlComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MssqlComponent extends SqlComponent {

    public function __construct() {
    	$this->setOpenIdentifier("[");
		$this->setCloseIdentifier("]");
    }
    

    /**
     * @param array $driver ex: mssal
     * @param array $host ex: abc.com:3306
     * @param array $dbName
     * @param array $charset
     */
    public function setDSN($driver = NULL, $host = NULL, $dbName = NULL, $charset = NULL) {
    	
    	$driver = isset($this->dbConfig['%driver']) ? $this->dbConfig['%driver'] : "dblib";  
    	$host = !is_null($host) ? $host : $this->dbConfig['%$host'];
    	$dbName = !is_null($dbName) ? $dbName : $this->dbConfig['%database'];
    	if (is_null($charset)) {
    		if (!is_null($this->dbConfig['%encoding'])) {
    			$charset = "charset={$this->dbConfig['%encoding']}";
    		} else {
    			$charset = "charset=utf8";
    		}
    	}
    	$this->dsn = "{$driver}:host={$host};dbname={$dbName};{$charset}";
    }
    

    /**
     *  @example
     *  $offset @int
     *  $interval @int
     *
     *  But, we use the following generic solution since we do not have a key.
     *
     *  SELECT TOP $interval * FROM tablename
     *  WHERE key NOT IN (
     *		SELECT TOP $offset key
     *		FROM tablename
     *		ORDER BY key
     *	);
     *
     *  After SQL 2005, database has ROW_NUMBER(), so we can use the following.
     *  That means that we can only support MS SQL 2005 or above.
     *
     *  SELECT * FROM
     *  (SELECT *, ROW_NUMBER() OVER (ORDER BY name) as row FROM table_name) a
     *  WHERE row > 5 and row <= 10
     *
     *  @param int $offset
     *  @param int $interval
     *  @return MssqlComponent
     *
     */
    public function limit($offset, $interval) {
    	$this->limitPart = "";
    	if (is_null($offset) && is_null($interval)) {
    		return $this;
    	}
    
    	$offset = (!is_null($offset)) ? $offset : 0;
    
    	$endNumber = $offset + $interval;
    	$this->limitPart = " (row > {$offset}) and (row <= {$endNumber})";
    	return $this;
    }
    
    /**
     * @see SqlComponent::composeSelectStatement()
     *
     * @param string $selectPart
     * @param string $joinOnParts
     * @param string $joinPart
     * @param string $joinOnPart
     * @param string $wherePart
     * @param string $groupPart
     * @param string $orderPart
     * @param string $limitPart
     * @return string
     */
    public function composeSelectStatement($selectPart, $joinOnParts, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart) {
    	$queryStmt = "";
    
    	if ($limitPart != "") {
    		if ($wherePart != "") {
    			$wherePart .= "AND {$limitPart}";
    		} else {
    			$wherePart = "WHERE {$limitPart}";
    		}
    		$selectParts = explode ("FROM", $selectPart);
    		$newSelectParts = $selectParts[0];
    		$selectFields = trim(str_ireplace("SELECT", "", $newSelectParts));
    		$tableName = $selectParts[1];
    		 
    		// (SELECT *, ROW_NUMBER() OVER (ORDER BY name) as row FROM table_name) a
    		$fromPart = " FROM (SELECT {$selectFields}, ROW_NUMBER() OVER ({$orderPart}) as row FROM {$tableName}) a ";
    		$queryStmt = $newSelectParts . $fromPart . $joinOnParts .  $joinPart
    		. $joinOnPart . $wherePart . $groupPart;
    	} else {
    	$queryStmt = $selectPart . $joinOnParts. $joinPart . $joinOnPart
    	. $wherePart . $groupPart . $orderPart;
    	}
    	return $queryStmt;
    	}
    
    	/**
    	* @see SqlComponent::execute()
    	*
    	* @param string $statement Default value = NULL
    	*/
    	public function execute($statement = NULL) {
    
    	$statement = is_null($statement) ? $this->getStatement() : $statement;
    	$con = mssql_connect($this->dbConfigArray['host'], $this->dbConfigArray['id'], $this->dbConfigArray['pwd']);
    	if (!$con) {
    	die('Could not connect: ' . mssql_get_last_message());
    	}
    	mssql_select_db($this->dbConfigArray['database'], $con);
    	$mssqlResult = mssql_query($statement);
        if (!$mssqlResult) {
            die('Could not query:' . mssql_get_last_message());
    	}
    
    	//wrapping the query result into an array
    	$resultArray = array();
    	while($row = mssql_fetch_array($mssqlResult, MSSQL_BOTH)) {
			$resultArray[] = $row;
            }
    
            mssql_free_result($mssqlResult);
            mssql_close($con);
            $this->cleanAll();
    
            //NOTE: For MSSQL, unlike MySQL, the raw mssql_query result cannot be passed and used.
            //So, in order to passing the whole result, we need to wrap the result by using mssql_fetch_array first
            return $resultArray;
    	}
    
    	/**
    	* @see SqlComponent::sqlEscape()
    	*
    	* @param string $content
    	*/
    	function sqlEscape($content) {
    	return $content;
    	}
    
}

?>
