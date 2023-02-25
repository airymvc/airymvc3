<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

namespace airymvc\app\lib\db;

/**
 * This class contains a set of databases.
 *
 * @package airymvc\app\lib\db\DatabaseFarm
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 *
 */
class SqlDbCopyFarm{
	
	protected $dbs;
	
	protected $masterDb;
	
	public function __construct($dbs) {
		$this->dbs = $dbs;
		$this->masterDb = $this->dbs[0];
	}
	
	/**
	 * Get the database component instance.
	 * @return object
	 */
	public function masterDb() {
		return $this->masterDb;
	}
	
	
	//-------------------------------------------------------
	//  SQL methods
	//-------------------------------------------------------
	
	/**
	 * Use to compose SQL SELECT query.
	 *
	 * @param array $columns The columm (field) names in the SQL select query.
	 * @param string $table The table name in the SQL select query.
	 * @param boolean $distinct Use distinct or not in the SQL select query.
	 * @return SqlDb
	 */
	public function select($columns, $table, $distinct = null) {
		$this->masterDb->select($columns, $table, $distinct);
		return $this;
	}
	
	/**
	 * Use to compose SQL WHERE part in a query.
	 *
	 * @param array $condition The columm (field) names in the SQL select query.
	 * @return SqlDb
	 */
	public function where($condition) {
		$this->masterDb->where($condition);
		return $this;
	}
	
	/**
	 * Use to compose SQL WHERE clause for the AND part in a query.
	 *
	 * @param string $opString The AND condition text.
	 * @return SqlDb
	 */
	public function andWhere($opString) {
		$this->masterDb->andWhere($opString);
		return $this;
	}
	
	/**
	 * Use to compose SQL WHERE clause for the OR part in a query.
	 *
	 * @param string $opString The OR condition text.
	 * @return object The instance itself.
	 */
	public function orWhere($opString) {
		$this->masterDb->orWhere($opString);
		return $this;
	}
	
	/**
	 * Use to compose SQL WHERE clause for the IN part in a query.
	 *
	 * @param string $opString The IN condition text.
	 * @return SqlDb
	 */
	public function inWhere($in) {
		$this->masterDb->inWhere($in);
		return $this;
	}
	
	/**
	 * Use to compose SQL INNER JOIN ON part in a query.
	 *
	 * @param string $table The table name.
	 * @param string $condition The condition text.
	 * @return SqlDb
	 */
	public function innerJoinOn($table, $condition) {
		$this->masterDb->innerJoinOn($table, $condition);
		return $this;
	}
	
	
	/**
	 * @deprecated Not suggested use.
	 */
	public function orJoinOn($condition) {
		$this->masterDb->orJoinOn($condition);
		return $this;
	}
	
	/**
	 * @deprecated Not suggested use.
	 */
	public function andJoinOn($condition) {
		$this->masterDb->andJoinOn($condition);
		return $this;
	}
	
	/**
	 * Use to compose SQL UPDATE query.
	 *
	 * @param array $columns The columm (field) names in the SQL select query.
	 * @param string $table The table name.
	 * @return SqlDb
	 */
	public function update($columns, $table) {
		$this->masterDb->update($columns, $table);
		return $this;
	}
	
	/**
	 * Use to compose SQL INSERT query.
	 *
	 * @param array $columns The columm (field) names in the SQL select query.
	 * @param string $table The table name.
	 * @return SqlDb
	 */
	public function insert($columns, $table) {
		$this->masterDb->insert($columns, $table);
		return $this;
	}
	
	/**
	 * Use to compose SQL DELETE query.
	 *
	 * @param string $table The table name.
	 * @return object The instance itself.
	 */
	public function delete($table) {
		$this->masterDb->delete($table);
		return $this;
	}
	
	/**
	 * Get a SQL query that is composed.
	 *
	 * @return string The SQL query.
	 */
	public function getStatement() {
		return $this->masterDb->getStatement();
	}
	
	/**
	 * Use to compose GROUP BY part
	 * @param string $column  The field is used for GROUP BY.
	 * @return string The SQL query.
	 */
	public function groupBy($column) {
		$this->masterDb->groupBy($column);
		return $this;
	}

	/**
	 * Use to compose LIMIT part in a SQL
	 * @param int $offset
	 * @param int $interval
	 * @return SqlDb
	 */
	public function limit($offset, $interval) {
		$this->masterDb->limit($offset, $interval);
		return $this;
	}
	
	/**
	 * Use to compose ORDER BY part
	 * @param string $column  The field is used for ORDER BY.
	 * @param boolean $ifDesc  Determine if it is DESC order.
	 * @return SqlDb
	 */
	public function orderBy($column, $ifDesc = NULL) {
		$this->masterDb->orderBy($column, $ifDesc);
		return $this;
	}
	

	
	//-------------------------------------------------------
	// PDO methods
	//-------------------------------------------------------
	
   /**
	* Call PDO::prepare()
	* @param string $statement
	* @param array $driverOptions
	* @return object
	*/
	public function prepare($statement, array $driverOptions = array()) {
		//return a prepareStatement here
		return $this->masterDb->prepare($statement, $driverOptions);
	}
	
	/**
	* Call PDO::beginTransaction()
	* @return SqlDbCopyFarm
	*/
	public function beginTransaction() {
		$this->masterDb->beginTransaction();
		return $this;
	}
	
	/**
	* Call PDO::rollBack() to roll back the transaction
	*/
	public function rollBack() {
		$this->pdoConn->rollBack();
	}
	
	 /**
	 * @return SqlDbCopyFarm
	 */
	public function commit() {
	   $this->masterDb->commit();
	   return $this;
	}
	
   /**
	* Call PDO::exec()
	* @param string $statement
	* @return SqlDbCopyFarm
	*/
	public function exec($statement = null) {
	  	//@TODO: determine which db to be search; update and insert and delete from all the database
	  	$this->masterDb->exec($statement);
	  	return $this;
	}
	
   /**
    * @param int $attribute
    * @param mixed $value
    * @return SqlDbCopyFarm
    */
	public function setAttribute($attribute, $value) {
	  	$this->masterDb->setAttribute($attribute, $value);
	  	return $this;
	}
	
   /**
	* @param int $attribute
	* @return mixed
	*/
	public function getAttribute($attribute) {
	  	return $this->masterDb->getAttribute($attribute);
    }
	
   /**
	* Call PDO::errorCode()
	*/
	public function errorCode() {
	  	return $this->masterDb->errorCode();
	}
	
   /**
	* Call PDO::errorInfo()
	*/
	public function errorInfo() {
		return $this->masterDb->errorInfo();
	}
	
   /**
	* Call PDO::getAvailableDrivers()
	*/
	public function getAvailableDrivers() {
		return $this->masterDb->getAvailableDrivers();
	}
	
   /**
	* Call PDO::inTransaction()
	*/
	public function inTransaction() {
		return $this->masterDb->inTransaction();
	}
	
	/**
	* @param string $name
	*/
	public function lastInsertId($name = NULL) {
		return $this->masterDb->lastInsertId($name = NULL);
	}
	
	/**
	* @param string $statement
	* @param string $fetchType
	* @param string $fetch
	* @param array $ctorargs
	*/
	public function query($statement, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
		return $this->masterDb->query($statement, $fetchType, $fetch, $ctorargs);
	}
	
	/**
	* @param string $str
	* @param int $parameterType Default value = PDO::PARAM_STR
	*/
	public function quote($str, $parameterType = PDO::PARAM_STR) {
		return $this->masterDb->quote($str, $parameterType);
	}
	
	/**
	* @see SqlDb::execute()
	*/
	public function execute($statement = NULL, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
		//@TODO: algorithm to decide which one to search
		return $this->masterDb->execute($statement, $fetchType, $fetch, $ctorargs);
	}
	
	/**
	* Call setAutoClose()
	* @param object $value
	*/
	public function setAutoClose($value) {
		return $this->masterDb->setAutoClose($value);
	}
	
	/**
	* Call closeConnection()
	*/
	public function closeConnection() {
	 	return $this->masterDb->closeConnection();
	}
	
	/**
	* Call setConnection()
	* @param string $dsn
	* @param string $userid
	* @param string $passwd
	*/
	public function setConnection($dsn = NULL, $userid = NULL, $passwd = NULL) {
		return $this->masterDb->setConnection();
	}
	

}
?>
