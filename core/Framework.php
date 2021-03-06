<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

namespace airymvc\core;

/**
 * This class defines all the variable that framework needs
 *
 * @package airymvc\core\Framework
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 *
 */
class Framework{

	/**
	 * @var array $appNames all the running application/project names
	 */
	static $appNames;
	
	
	static $apps;
	
	
	/**
	 * Get the path of the airymvc folder.
	 * 
	 * @return string the root path.
	 */
	public static function root() {
		return dirname(dirname(__FILE__));
	}
	
	/**
	 * Get the path of the airymvc config folder.
	 *
	 * @return string the config folder.
	 */
	public static function configFolder() {
		return Framework::root() . DIRECTORY_SEPARATOR . "config";
	}
	
	/**
	 * Get the path of the airymvc config file.
	 *
	 * @return string the config file path.
	 */
	public static function configFile($env = null) {
		if ($env == null) {
			$env = Framework::environment();
		}
		return Framework::configFolder() . DIRECTORY_SEPARATOR . "config.{$env}.json";
	}
	
	/**
	 * Get running environment
	 */
	public static function environment() {
		$envArray = json_decode(file_get_contents(Framework::configMapFile()), true);
		$env = "dev";
		foreach ($envArray['%env_map'] as $envElem) {
			if ($envElem["%server_name"] == Framework::getServerName()) {
				$env = $envElem["%env"];
			}
		}
		return $env;
	}
	
	/**
	 * Get the path of the airymvc config map file.
	 *
	 * @return string the config map file path.
	 */
	public static function configMapFile() {
		return Framework::configFolder() . DIRECTORY_SEPARATOR . "config.map.json";
	}
	
	/**
	 * Get all the running application (project) names
	 *
	 * @return array application names
	 */	
	public static function appNames() {
		return Framework::$appNames;
	}
	
	
	/**
	 * Get the application object
	 * 
	 * @retrun airymvc\core\Application 
	 */
	public static function app($name) {
		foreach (Framework::$apps as $app) {
			if ($app->name() == $name) {
				return $app;
			}
		}
		return NULL;
	}
	
	/**
	 * Get the application name by ServerName
	 *
	 * @retrun airymvc\core\Application
	 */
	public static function appName($serverName) {
		foreach (Framework::$apps as $app) {
			if ($app->serverName() == $serverName) {
				return $app->name();
			}
		}
		return NULL;
	}
	
	/**
	 * Set the application object
	 *
	 * @param airymvc\core\Application $app
	 */
	public static function setApp($app) {
		Framework::$apps[$app->name()] = $app;
	}
	
	/**
	 * Get the app running server name 
	 */
	public static function getServerName() {
		exec("uname -n", $out);
		return $out[0];
	}
}
?>
