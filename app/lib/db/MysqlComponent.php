<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\app\lib\db;

require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'db' .DIRECTORY_SEPARATOR . 'SqlComponent.php';

use airymvc\app\lib\db\SqlComponent;
 
/**
 * This handles the MYSQL database using PDO connection.
 *
 * @filesource
 * @package airymvc\app\lib\db\MssqlComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MysqlComponent extends SqlComponent {
	
    public function __construct() {
		$this->setOpenIdentifier("");
		$this->setCloseIdentifier("");
    }
    
    
    /**
     * @param array $host ex: abc.com:3306
     * @param array $dbName
     * @param array $charset
     */
    public function setDSN($host = NULL, $dbName = NULL, $charset = NULL) {
    	$dbName = !is_null($dbName) ? $dbName : $this->dbConfig['%database'];
    	$host = !is_null($host) ? $host : $this->dbConfig['%host'];
    	$hostParts = explode(":", $host);
    	$hostName = $hostParts[0];
    	$portNumber = isset($hostParts[1]) ? $hostParts[1] : "3306";
    	if (is_null($charset)) {
    		if (!is_null($this->dbConfig['%encoding'])) {
    			$charset = "charset={$this->dbConfig['%encoding']}";
    		} else {
    			$charset = "charset=utf8";
    		}
    	}
    	 
    	$this->dsn = "mysql:host={$hostName};port={$portNumber};dbname={$dbName};{$charset}";

    }

    /**
     * @see PdoSqlComponent::sqlEscape()
     */
    function sqlEscape($content) {
        return $content;
    }    
}

?>
