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
 * @package framework\app\library\db\MysqlComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MysqlComponent extends MysqlCommon{
    
	/**
	 * @see MysqlCommon::execute()
	 */
    public function execute($statement = NULL) {

    	$statement = is_null($statement) ? $this->getStatement() : $statement;
        $con = mysql_connect($this->dbConfigArray['host'], $this->dbConfigArray['id'], $this->dbConfigArray['pwd']);
        mysql_set_charset($this->dbConfigArray['encoding'] ,$con);
          
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($this->dbConfigArray['database'], $con);
        $mysql_results = mysql_query($statement);

        if (!$mysql_results) {
            die('Could not query:' . mysql_error());
        }
        mysql_close($con);
        $this->cleanAll();
        
        return $mysql_results;
    }

	/**
	 * @see SqlComponent::sqlEscape()
	 */
    function sqlEscape($content) {
        /**
         * Need to add connection in order to avoid ODBC errors here 
         */
        $con = mysql_connect($this->dbConfigArray['host'],$this->dbConfigArray['id'],$this->dbConfigArray['pwd']);
        mysql_set_charset($this->dbConfigArray['encoding'] ,$con);
        //check if $content is an array
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = mysql_real_escape_string($value);
            }
        } else {
            //check if $content is not an array
            $content = mysql_real_escape_string($content);
        }
        mysql_close($con);
        return $content;
    }


}

?>
