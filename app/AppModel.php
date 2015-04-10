<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\app;

use airymvc\core\Application;
use airymvc\core\Config;
use airymvc\core\Mvc;
use airymvc\app\lib\db\SqlDb;
use airymvc\app\lib\db\MongoDb;
use airymvc\app\lib\db\SqlDbCopyFarm;

/**
 * This is the controller class that is used for intializing the instance and set variables.
 *
 * @package airymvc\app\AppModel
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class AppModel {
	
	protected $db;
	protected $dbs;

    /**
     * To deal with the database config(s)
     * 
     * @return object database 
     */
    public function initDb() {
		$config = Mvc::currentApp()->config();
		foreach ($config->db() as $dbConfig) {
			if ($dbConfig["%type"] != "mongo") {
				$database = new SqlDb();
			} else {
				$database = new MongoDb();
			}
			$database->initDb($dbConfig);
			$this->dbs[] = $database;
		}
		if ($config->dbMode() == "copy") {
			$masterDbConfig = $config->db(0);
			if ($masterDbConfig["%type"] != "mongo") {
				$this->db = new SqlDbCopyFarm($this->dbs);
			}
		} else {
			$this->db = $this->dbs[0];
		}
    }
    
    public function db($index = NULL) {
    	if (is_null($index)) {
    		return $this->dbs;
    	}
    	return $this->dbs[$index];
    }

}

?>
