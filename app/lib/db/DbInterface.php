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
 * This interface for database access.
 *
 * @package airymvc\app\lib\db\DbInterface
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
interface DbInterface {
	
	/**
	 * @param array $columns
	 * @param string $table
	 * @param number $distinct
	 */
    public function select($columns, $table, $distinct = 0);
    
    
    /**
     * @param string $condition
     */
    public function where($condition);
    
    /**
     * @param string $opString
     */
    public function andWhere($opString);
    
    /**
     * @param string $opString
     */
    public function orWhere($opString);
    
    /**
     * @param string $in
     */
    public function inWhere($in);
    
    /**
     * @param string $table
     * @param string $condition
     */
    public function innerJoinOn($table, $condition);
    
    /**
     * @param array $columns
     * @param string $table
     */
    public function update($columns, $table);
    
    /**
     * @param array $columns
     * @param string $table
     */
    public function insert($columns, $table);  
    
    /**
     * @param string $table
     */
    public function delete($table);
    
    
    /**
     * @param string $statement
     */
    public function execute($statement = NULL);
    
    /**
     *  Get the SQL statement.
     */
    public function getStatement();
    
    /**
     * @param string $column
     */
    public function groupBy($column);
    
    /**
     * @param int $offset
     * @param int $interval
     */
    public function limit($offset, $interval);

    /**
     * @param string $column
     * @param string|boolean $if_desc
     */
    public function orderBy($column, $if_desc = NULL);

	
	
}