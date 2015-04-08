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
class DatabaseFarm{
	

	 protected $configArray;
	 
	 protected $dbComponent;
     
     public function __construct($configArray) {
     	$this->setConfigArray($configArray);
     }
     
     public function configArray() {
		return $this->configArray;
	 }
	 
	 public function setConfigArray($configArray) {
		$this->configArray = $configArray;
		return $this;
	 }
	 
	 
	 /**
	  * Set the database component object.
	  * @param array $config
	  */
	 public function setComponent($config) {
	 	//initialize the object based on the database type
	 	$className = ucfirst(strtolower($config['type'])) . 'Component';
	 	if (strtolower($config['type']) == "mysql") {
	 		$className = ucfirst(strtolower($config['connection_type'])) . 'Component';
	 	}
	 	 
	 	$this->dbComponent = new $className();
	 	$this->dbComponent->setConfig($config);
	 }
	 
	 
	 //-------

	 /**
	  * Get the database component instance.
	  * @return object
	  */
	 public function getDbComponent() {
	 	return $this->dbComponent;
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
	 	$this->dbComponent = new $className();
	 	$this->dbComponent->configConnection($config);
	 }
	 
	 
	 //--------
	 
	 
	 
	 
	 /**
	  * Use to compose SQL SELECT query.
	  *
	  * @param array $columns The columm (field) names in the SQL select query.
	  * @param string $table The table name in the SQL select query.
	  * @param boolean $distinct Use distinct or not in the SQL select query.
	  * @return AbstractAccess
	  */
	 public function select($columns, $table, $distinct = null) {
	 	$this->dbComponent->select($columns, $table, $distinct);
	 	return $this;
	 }
	 
	 /**
	  * Use to compose SQL WHERE part in a query.
	  *
	  * @param array $condition The columm (field) names in the SQL select query.
	  * @return AbstractAccess
	  */
	 public function where($condition) {
	 	$this->dbComponent->where($condition);
	 	return $this;
	 }
	 
	 /**
	  * Use to compose SQL WHERE clause for the AND part in a query.
	  *
	  * @param string $opString The AND condition text.
	  * @return AbstractAccess
	  */
	 public function andWhere($opString) {
	 	$this->dbComponent->andWhere($opString);
	 	return $this;
	 }
	 
	 /**
	  * Use to compose SQL WHERE clause for the OR part in a query.
	  *
	  * @param string $opString The OR condition text.
	  * @return object The instance itself.
	  */
	 public function orWhere($opString) {
	 	$this->dbComponent->orWhere($opString);
	 	return $this;
	 }
	 
	 /**
	  * Use to compose SQL WHERE clause for the IN part in a query.
	  *
	  * @param string $opString The IN condition text.
	  * @return AbstractAccess
	  */
	 public function inWhere($in) {
	 	$this->dbComponent->inWhere($in);
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
	 	$this->dbComponent->innerJoinOn($table, $condition);
	 	return $this;
	 }
	 
	 /**
	  * @deprecated Not suggested use.
	  */
	 public function innerJoin($tables) {
	 	$this->dbComponent->innerJoin($tables);
	 	return $this;
	 }
	 
	 /**
	  * @deprecated Not suggested use.
	  */
	 public function orJoinOn($condition) {
	 	$this->dbComponent->orJoinOn($condition);
	 	return $this;
	 }
	 
	 /**
	  * @deprecated Not suggested use.
	  */
	 public function andJoinOn($condition) {
	 	$this->dbComponent->andJoinOn($condition);
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
	 	$this->dbComponent->update($columns, $table);
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
	 	$this->dbComponent->insert($columns, $table);
	 	return $this;
	 }
	 
	 /**
	  * Use to compose SQL DELETE query.
	  *
	  * @param string $table The table name.
	  * @return object The instance itself.
	  */
	 public function delete($table) {
	 	$this->dbComponent->delete($table);
	 	return $this;
	 }
	 
	 /**
	  * Use to execute a SQL query.
	  *
	  * @param string $statement The SQL query.
	  * @return object The result of the query.
	  */
	 public function execute($statement = NULL) {
	 	return $this->dbComponent->execute($statement);
	 }
	 
	 /**
	  * Get a SQL query that is composed.
	  *
	  * @return string The SQL query.
	  */
	 public function getStatement() {
	 	return $this->dbComponent->getStatement();
	 }
	 
	 /**
	  * Use to compose GROUP BY part
	  * @param string $column  The field is used for GROUP BY.
	  * @return string The SQL query.
	  */
	 public function groupBy($column) {
	 	$this->dbComponent->groupBy($column);
	 	return $this;
	 }
	 
	 /**
	  * @deprecated Not suggested use.
	  */
	 public function joinOn($condition) {
	 	$this->dbComponent->joinOn($condition);
	 	return $this;
	 }
	 
	 /**
	  * Use to compose LIMIT part in a SQL
	  * @param int $offset
	  * @param int $interval
	  * @return AbstractAccess
	  */
	 public function limit($offset, $interval) {
	 	$this->dbComponent->limit($offset, $interval);
	 	return $this;
	 }
	 
	 /**
	  * Use to compose ORDER BY part
	  * @param string $column  The field is used for ORDER BY.
	  * @param boolean $ifDesc  Determine if it is DESC order.
	  * @return AbstractAccess
	  */
	 public function orderBy($column, $ifDesc = NULL) {
	 	$this->dbComponent->orderBy($column, $ifDesc);
	 	return $this;
	 }

	 
	 /**
	  * Call PDO::prepare()
	  * @param string $statement
	  * @param array $driverOptions
	  * @return object
	  */
	 public function prepare($statement, array $driverOptions = array()) {
	 	//return a prepareStatement here
	 	return $this->dbComponent->prepare($statement, $driverOptions);
	 }
	 
	 /**
	  * Call PDO::beginTransaction()
	  * @return PdoAccess
	  */
	 public function beginTransaction() {
	 	$this->dbComponent->beginTransaction();
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
	 	$this->dbComponent->commit();
	 	return $this;
	 }
	 
	 /**
	  * Call PDO::exec()
	  * @param string $statement
	  * @return PdoAccess
	  */
	 public function exec($statement = null) {
	 	$this->dbComponent->exec($statement);
	 	return $this;
	 }
	 
	 /**
	  * @param int $attribute
	  * @param mixed $value
	  * @return PdoAccess
	  */
	 public function setAttribute($attribute, $value) {
	 	$this->dbComponent->setAttribute($attribute, $value);
	 	return $this;
	 }
	 
	 /**
	  * @param int $attribute
	  * @return mixed
	  */
	 public function getAttribute($attribute) {
	 	return $this->dbComponent->getAttribute($attribute);
	 }
	 
	 /**
	  * Call PDO::errorCode()
	  */
	 public function errorCode() {
	 	return $this->dbComponent->errorCode();
	 }
	 
	 /**
	  * Call PDO::errorInfo()
	  */
	 public function errorInfo() {
	 	return $this->dbComponent->errorInfo();
	 }
	 
	 /**
	  * Call PDO::getAvailableDrivers()
	  */
	 public function getAvailableDrivers() {
	 	return $this->dbComponent->getAvailableDrivers();
	 }
	 
	 /**
	  * Call PDO::inTransaction()
	  */
	 public function inTransaction() {
	 	return $this->dbComponent->inTransaction();
	 }
	 
	 /**
	  * @param string $name
	  */
	 public function lastInsertId($name = NULL) {
	 	return $this->dbComponent->lastInsertId($name = NULL);
	 }
	 
	 /**
	  * @param string $statement
	  * @param string $fetchType
	  * @param string $fetch
	  * @param array $ctorargs
	  */
	 public function query($statement, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
	 	return $this->dbComponent->query($statement, $fetchType, $fetch, $ctorargs);
	 }
	 
	 /**
	  * @param string $str
	  * @param int $parameterType Default value = PDO::PARAM_STR
	  */
	 public function quote($str, $parameterType = PDO::PARAM_STR) {
	 	return $this->dbComponent->quote($str, $parameterType);
	 }
	 
	 /**
	  * @see AbstractAccess::execute()
	  */
	 public function execute($statement = NULL, $fetchType = NULL, $fetch = NULL, array $ctorargs = NULL) {
	 	return $this->dbComponent->execute($statement, $fetchType, $fetch, $ctorargs);
	 }
	 
	 /**
	  * Call setAutoClose()
	  * @param object $value
	  */
	 public function setAutoClose($value) {
	 	return $this->dbComponent->setAutoClose($value);
	 }
	 
	 /**
	  * Call closeConnection()
	  */
	 public function closeConnection() {
	 	return $this->dbComponent->closeConnection();
	 }
	 
	 /**
	  * Call setConnection()
	  * @param string $dsn
	  * @param string $userid
	  * @param string $passwd
	  */
	 public function setConnection($dsn = NULL, $userid = NULL, $passwd = NULL) {
	 	return $this->dbComponent->setConnection();
	 }
}
?>
