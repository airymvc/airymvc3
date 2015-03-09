<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the MYSQL database using PDO connection.
 *
 * @filesource
 * @package framework\app\library\db\PdoMssqlComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class PdoMysqlComponent extends PdoSqlComponent {
	
    function __construct() {

    	$this->setIdentifier();
    }
    
    /**
     * @param array $dbConfigArray
     */
    public function configConnection($dbConfigArray) {
    	$hostArray = explode(":", $dbConfigArray['host']);
    	$this->host = $hostArray[0];
    	$this->port = isset($hostArray[1]) ? $hostArray[1] : "3306";
    	$charset = isset($dbConfigArray['encoding']) ? "charset={$dbConfigArray['encoding']}" : "charset=utf8";
    	
    	$this->dsn = "{$dbConfigArray['dbtype']}:host={$this->host};port={$this->port};dbname={$dbConfigArray['database']};{$charset}";
    	$this->setConnection($this->dsn, $dbConfigArray['id'], $dbConfigArray['pwd']);
    }

    /**
     * @see PdoSqlComponent::sqlEscape()
     */
    function sqlEscape($content) {
        return $content;
    }    
}

?>
