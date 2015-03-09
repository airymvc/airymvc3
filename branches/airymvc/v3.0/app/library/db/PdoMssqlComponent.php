<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the MSSQL database using PDO connection.
 *
 * @filesource
 * @package framework\app\library\db\PdoMssqlComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class PdoMssqlComponent extends PdoSqlComponent {

	/**
	 * Mssql component is used for exporting limit
	 * @var MssqlComponent $mssqlComponent
	 */
	protected $mssqlComponent;

    function __construct() {
    	$this->mssqlComponent = new MssqlComponent();
		$this->setIdentifier();
    }
    
    /**
     * @param array $dbConfigArray
     */
    public function configConnection($dbConfigArray) {
		
		$driver = isset($dbConfigArray['driver']) ? $dbConfigArray['driver'] : "dblib";
		$charset = isset($dbConfigArray['encoding']) ? "charset={$dbConfigArray['encoding']}" : "charset=utf8";
		
		$this->dsn = "{$driver}:host={$dbConfigArray['host']};dbname={$dbConfigArray['database']};{$charset}";
		$this->setConnection($this->dsn, $dbConfigArray['id'], $dbConfigArray['pwd']);
    }
        
    /**
     * @see SqlComponent::limit()
     */
    public function limit($offset, $interval) {
		$this->mssqlComponent->limit($offset, $interval);
        $this->limitPart = $this->mssqlComponent->getLimitPart();
        return $this;
    }
    
    /**
     * @see SqlComponent::composeSelectStatement()
     */
    public function composeSelectStatement($selectPart, $joinOnParts, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart) {
        return $this->mssqlComponent->composeSelectStatement($selectPart, $joinOnParts, $joinPart, $joinOnPart, $wherePart, $groupPart, $orderPart, $limitPart);
    } 
       
	/**
	 * @see PdoSqlComponent::sqlEscape()
	 */
    function sqlEscape($content) {
        return $content;
    }    
}

?>
