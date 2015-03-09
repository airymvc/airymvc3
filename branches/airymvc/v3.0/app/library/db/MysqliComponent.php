<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This abstract class is used for composing each kind of the SQL statement.
 *
 * @filesource
 * @package framework\app\library\db\MysqliComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MysqliComponent extends MysqlCommon {

	/**
	 * @var int $port
	 */
	private $port = 3306;
	
	/**
	 * @var string $host
	 */
	private $host;
	
	/**
	 * @see MysqlCommon::execute()
	 */
    public function execute() {
		$hostArray = explode(":", $this->dbConfigArray['host']);    	
		$this->host = $hostArray[0];
		$this->port = $hostArray[1];
        $con = new mysqli($this->host, 
        				  $this->dbConfigArray['id'], 
        				  $this->dbConfigArray['pwd'], 
        				  $this->dbConfigArray['database'],
        				  $this->port);
        				  
		$result = $con->query($this->getStatement());
        $con->close();
        
        $this->cleanAll();
        
        return $result;
    }
    /**
     * @see SqlComponent::sqlEscape()
     */
    function sqlEscape($content) {
        /**
         * Need to add connection in order to avoid ODBC errors here 
         */
        $con = new mysqli($this->host, 
        				  $this->dbConfigArray['id'], 
        				  $this->dbConfigArray['pwd'], 
        				  $this->dbConfigArray['database'],
        				  $this->port);
        mysqli_set_charset($con, strtolower($this->dbConfigArray['encoding']));
        //check if $content is an array
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = mysqli_real_escape_string($con, $value);
            }
        } else {
            //check if $content is not an array
            $content = mysqli_real_escape_string($con, $content);
        }
        mysqli_close($con);
        return $content;
    }

}

?>
