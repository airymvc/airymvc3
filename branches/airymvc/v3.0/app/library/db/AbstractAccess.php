<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

/**
 * @see framework\app\library\view\Layout
 */
require dirname(dirname(__FILE__)) . "/view/Layout.php";

/**
 * This is the abstract class of the database access.
 *
 * @package framework\app\library\db\AbstractAccess
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class AbstractAccess {
	
	/**
	 * @var mixed $_dbComponent
	 */
	protected $_dbComponent;
	
	/**
	 * The configuration array that is from the config.ini
	 * @var array $dbConfigArray
	 */
	protected $dbConfigArray;

	/**
	 * Use to compose SQL SELECT query.
	 * 
	 * @param array $columns The columm (field) names in the SQL select query.
	 * @param string $table The table name in the SQL select query.
	 * @param boolean $distinct Use distinct or not in the SQL select query.
	 * @return AbstractAccess
	 */	
   	public function select($columns, $table, $distinct = null) {
   		$this->_dbComponent->select($columns, $table, $distinct);
   		return $this;
   	}
   	
   	/**
   	 * Use to compose SQL WHERE part in a query.
   	 *
   	 * @param array $condition The columm (field) names in the SQL select query.
   	 * @return AbstractAccess
   	 */
   	public function where($condition) {
   		$this->_dbComponent->where($condition);
   		return $this;
   	}

    /**
     * Use to compose SQL WHERE clause for the AND part in a query.
     *
     * @param string $opString The AND condition text.
     * @return AbstractAccess
     */
    public function andWhere($opString) {
    	$this->_dbComponent->andWhere($opString);
   		return $this;
    }
    
    /**
     * Use to compose SQL WHERE clause for the OR part in a query.
     *
     * @param string $opString The OR condition text.
     * @return object The instance itself.
     */
    public function orWhere($opString) {
    	$this->_dbComponent->orWhere($opString);
   		return $this;
    }
    
    /**
     * Use to compose SQL WHERE clause for the IN part in a query.
     *
     * @param string $opString The IN condition text.
     * @return AbstractAccess
     */
    public function inWhere($in) {
    	$this->_dbComponent->inWhere($in);
   		return $this;
    }

    /**
     * Use to compose SQL INNER JOIN ON part in a query.
     *
     * @param string $table The table name.
     * @param string $condition The condition text.
     * @return AbstractAccess
     */
    public function innerJoinOn($table, $condition) {
    	$this->_dbComponent->innerJoinOn($table, $condition);
   		return $this;
    }
    
    /**
     * @deprecated Not suggested use.
     */
    public function innerJoin($tables) {
    	$this->_dbComponent->innerJoin($tables);
   		return $this;
    }
    
    /**
     * @deprecated Not suggested use.
     */   
    public function orJoinOn($condition) {
    	$this->_dbComponent->orJoinOn($condition);
   		return $this;    	
    }
    
    /**
     * @deprecated Not suggested use.
     */     
    public function andJoinOn($condition) {
    	$this->_dbComponent->andJoinOn($condition);
   		return $this;    	
    }

    /**
     * Use to compose SQL UPDATE query.
     *
     * @param array $columns The columm (field) names in the SQL select query.
     * @param string $table The table name.
     * @return AbstractAccess
     */
    public function update($columns, $table) {
    	$this->_dbComponent->update($columns, $table);
   		return $this;    	
    }

    /**
     * Use to compose SQL INSERT query.
     *
     * @param array $columns The columm (field) names in the SQL select query.
     * @param string $table The table name.
     * @return AbstractAccess
     */
    public function insert($columns, $table) {
    	$this->_dbComponent->insert($columns, $table);
   		return $this;    	
    }

    /**
     * Use to compose SQL DELETE query.
     *
     * @param string $table The table name.
     * @return object The instance itself.
     */
    public function delete($table) {
    	$this->_dbComponent->delete($table);
   		return $this;    	
    }

    /**
     * Use to execute a SQL query.
     *
     * @param string $statement The SQL query.
     * @return object The result of the query.
     */
    public function execute($statement = NULL) {
    	return $this->_dbComponent->execute($statement);   	
    }
    
    /**
     * Get a SQL query that is composed.
     * 
     * @return string The SQL query.
     */
    public function getStatement() {
    	return $this->_dbComponent->getStatement();
    }
    
    /**
     * Use to compose GROUP BY part
     * @param string $column  The field is used for GROUP BY.
     * @return string The SQL query.
     */
    public function groupBy($column) {
    	$this->_dbComponent->groupBy($column);
   		return $this;    	
    }
    
    /**
     * @deprecated Not suggested use.
     */     
    public function joinOn($condition) {
    	$this->_dbComponent->joinOn($condition);
   		return $this;
    }
    
    /**
     * Use to compose LIMIT part in a SQL
     * @param int $offset  
     * @param int $interval  
     * @return AbstractAccess
     */
    public function limit($offset, $interval) {
    	$this->_dbComponent->limit($offset, $interval);
   		return $this;
    }

    /**
     * Use to compose ORDER BY part
     * @param string $column  The field is used for ORDER BY.
     * @param boolean $ifDesc  Determine if it is DESC order.
     * @return AbstractAccess
     */
    public function orderBy($column, $ifDesc = NULL) {
    	$this->_dbComponent->orderBy($column, $ifDesc);
   		return $this;
    }
    
    /**
     * Get the database component instance.
     * @return object 
     */
    public function getDbComponent() {
    	return $this->_dbComponent;
    }
}

?>
