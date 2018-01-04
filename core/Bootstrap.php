<?php
/**
 * AiryMVC Framework
 * 
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

namespace airymvc\core;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Framework.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Application.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Route.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'MvcValue.php';

use airymvc\core\Framework;
use airymvc\core\Application;
use airymvc\core\Config;
use airymvc\core\Route;
use airymvc\core\MvcValue;


/**
 * This class handles all the path variables that need to be initialized.
 *
 * @package airymvc\core\Bootstrap
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Bootstrap {
	
    /**
     * This initializes what framework needs.
     */
    public static function init() {
    	    	
    	//Get framework application names
    	//Throw out the error if the json cannot be decoded
    	$configArray = json_decode(file_get_contents(Framework::configFile()), TRUE);
    	
		$names = array();
		$apps = array();
		$allAppRoutes = array();
		$allAppRoutesNoOverwritten = array();

		foreach ($configArray['%applications'] as $application) {
			$keys = array_keys($application);
			$names[] = $keys[0];
			$appSettings = $application[$keys[0]];
			//server name & document root & relative path
			$serverName = $appSettings[0];
			$docRoot = $appSettings[1];
			$pathName = "/";
			if (substr($appSettings[2], 0, 1) != "/") {
				$pathName .= $appSettings[2];
			}
			$app = new Application($keys[0], $serverName, $docRoot, $pathName);
			
			//** Get the application's application config
			$appSpecificConfigArray = NULL;
			if (!is_null($app->appConfigFile())) {
				$appSpecificConfigArray = json_decode(file_get_contents($app->appConfigFile()), TRUE);
				if (empty($appSpecificConfigArray)) {
					error_log("JSON Decode Error for application specific config file - " . $app->appConfigFile());
				}
			}

			//Get the framework's application config
			$finalAppConfigArray = array();
			foreach ($configArray["%application_configs"] as $appConfigArray) {
				if ($appConfigArray["%name"] == $keys[0]) {					
					$finalAppConfigArray = $appConfigArray;
				}
			}
		
			//Overwrite the application_configs by application's config array
			if (isset($appSpecificConfigArray['%application_configs']) && isset($appSpecificConfigArray['%application_configs'][0])) {
				$finalAppConfigArray = array_replace_recursive($finalAppConfigArray, $appSpecificConfigArray['%application_configs'][0]);
			}
			
			$app->setConfig($finalAppConfigArray);
			$apps[$keys[0]] = $app;
			
			//Get all routes from application specific config
			$appRoutePrefix = isset($appSpecificConfigArray["%route_prefix"]) ? $appSpecificConfigArray["%route_prefix"] : "";
	
			if (isset($appSpecificConfigArray["%routes"])) {
				foreach ($appSpecificConfigArray["%routes"] as $route => $appRoute) {
					if (isset($allAppRoutesNoOverwritten[$appRoutePrefix.$route])) {
						$existAppNames = $allAppRoutesNoOverwritten[$appRoutePrefix.$route]['application'];
						$existAppNames[] = $appRoute[0];
						$allAppRoutesNoOverwritten[$appRoutePrefix.$route]['application'] = $existAppNames;
						$allAppRoutesNoOverwritten[$appRoutePrefix.$route]['path'][$appRoute[0]] = $appRoute;
					} else {
						$allAppRoutesNoOverwritten[$appRoutePrefix.$route] = array("application" => array($appRoute[0]), 
								                                   "path"=> array($appRoute[0] => $appRoute)
						                                          );
					}
				}
				$allAppRoutes = array_replace_recursive($allAppRoutes, $appSpecificConfigArray["%routes"]);
			}

		}

		Framework::$appNames = $names;
		Framework::$apps = $apps;

		//process routing table here
		$table = array();
		$routeParams = array();
		
		//var_dump($allAppRoutesNoOverwritten);
		//error_log(print_r($allAppRoutes, true));
		
		$allRoutes = array();
		if (isset($configArray['%routes'])) {
			//overwrite the routes
			foreach ($configArray["%routes"] as $route => $appRoute) {
				if (isset($allRoutes[$route])) {
					$existAppNames = $allRoutes[$route]['application'];
					$existAppNames[] = $appRoute[0];
					$allRoutes[$route]['application'] = $existAppNames;
					$allRoutes[$route]['path'][$appRoute[0]] = $appRoute;
				} else {
					$allRoutes[$route] = array("application" => array($appRoute[0]),
							                   "path"=> array($appRoute[0] => $appRoute)
						);
				}
			}
			
			//var_dump($allAppRoutesNoOverwritten);

			if (!empty($allAppRoutesNoOverwritten)) {
				foreach ($allAppRoutesNoOverwritten as $route => $routeInfo) {
					if (isset($allRoutes[$route])) {
						foreach ($routeInfo['path'] as $appName => $routePath) {
							//overwritten by application specific route (config file)
							if (isset($allRoutes[$route]['path'][$appName])) {
								$allRoutes[$route]['path'][$appName] = $routePath;
							} else {
								$existAppNames = $allRoutes[$route]['application'];
								$existAppNames[] = $appName;
								$allRoutes[$route]['application'] = $existAppNames;
								$allRoutes[$route]['path'][$appName] = $routePath;							
							}
						}
					} else {
						$allRoutes[$route] = $routeInfo;
					}
				}				
			}
			
			//var_dump($allRoutes);
			//var_dump($apps);
			
			foreach ($allRoutes as $routeUrl => $allRouteVar) {
				foreach ($allRouteVar['application'] as $appName) {
					if (isset($apps[$appName])) {
						$appObj = $apps[$appName];
						$routeVar = $allRouteVar['path'][$appName];
						$mvcValue = new MvcValue();
						$mvcValue->setNames($appObj->relativePath(), $routeVar[0], $routeVar[1], Route::fromHyphenToCamelCase($routeVar[2], TRUE), Route::fromHyphenToCamelCase($routeVar[3]));
						$table[$routeUrl] = $mvcValue;
						$table[$appName.$routeUrl] = $mvcValue;
						if (isset($routeVar[4])) {
						    $routeParams[$routeUrl] = $routeVar[4];
						    $routeParams[$appName.$routeUrl] = $routeVar[4];
						}
					}
				}
			}
		}
		Route::$routingTable = $table;
		Route::$routingParams = $routeParams;
		
		//var_dump(Route::$routingTable);
		//var_dump(Route::$routingParams);

		include "AutoLoader.php";

    }
    
   
}

?>
