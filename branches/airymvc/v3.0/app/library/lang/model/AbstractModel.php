<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the framework log.
 *
 * @package framework\app\library\model\AbstractModel
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
abstract class AbstractModel {
    
    /**
     * @var object $db Database object. 
     */
    public $db;

    /**
     * * @var object $multiDb Array of database objects. 
     */
    public $multiDb = array();
    
    public function initialDB(){}
    
}

?>
