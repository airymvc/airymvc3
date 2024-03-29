<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */

namespace airymvc\core;

require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Application.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'AiryException.php';
use airymvc\core\AiryException;
use airymvc\core\Application;

/**
 * This class handles all the routings.
 *
 * @package airymvc\core\Route
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 *
 */
class Route{

	public static $routingTable;
	
	public static $routingParams;

	public static function routingTable() {
		return Route::$routingTable;
	}
	
	public static function routingParams() {
		return Route::$routingParams;
	}
		
	public static function resolveRoute($request) {
		$matchRoute = NULL;
		$runMvc = NULL;
		//get the match URI
		error_log(print_r(Route::routingTable(), true), 0);
		foreach (Route::routingTable() as $route => $mvc) {
			$serverName = $request->serverName();
			$app = Framework::appName($serverName);
			$appRequestURI = $app.$request->requestURI();
			$pos = strpos($appRequestURI, $route);
			if ($pos !== FALSE && $pos == 0) {
				$checkAppRequestURI = str_replace($route, "", $appRequestURI);
				if (substr($checkAppRequestURI, 0, 1) == "" || 
					substr($checkAppRequestURI, 0, 1) == "?" || 
					substr($checkAppRequestURI, 0, 1) == "/") {
					$runMvc = $mvc;
					$matchRoute = $route;
					break;
				}
			}
		}

		//Has matches of routing table; set the parameters in the request
		if (!is_null($runMvc) && !is_null($matchRoute)) {
			$idx = strlen($matchRoute);
			$restURI = substr($request->requestURI(), $idx);
			if (substr($restURI, 0, 1) == "/") {
				$restURI = substr($restURI, 1);
			}

			$uriParts = explode("/", $restURI);
			if (count($uriParts) >= 2) {
				for ($i=0; $i<count($uriParts); $i=$i+2) {
					$uriParts[$i+1] = isset($uriParts[$i+1]) ? $uriParts[$i+1] : NULL;
					$request->setParam($uriParts[$i],  $uriParts[$i+1]);
					$request->setGETParam($uriParts[$i],  $uriParts[$i+1]);
				}
			}
			$routeParameters = Route::routingParams();
			if (isset($routeParameters[$matchRoute])) {
				$useEqual = FALSE;
				//case #1: ?key1=value1&key2=value2
				$options = ltrim($routeParameters[$matchRoute], "?");
				$kvs = explode("&", $options);
				foreach ($kvs as $kv) {
					$kvParts = explode("=", $kv);
					if (count($kvParts) > 1) {
						$request->setParam($kvParts[0],  $kvParts[1]);
						$request->setGETParam($kvParts[0],  $kvParts[1]);
						$useEqual = TRUE;
					}
				}
				//case #2: /key1/value1/key2/value2
				if (!$useEqual) {
				    $options = ltrim($routeParameters[$matchRoute], "/");
			   	    $elems = explode("/", $options);
				    if (count($elems) > 1) {
				        for ($i=0; $i<count($elems); $i=$i+2) {
					         $request->setParam($elems[$i],  $elems[$i+1]);
					       	 $request->setGETParam($elems[$i],  $elems[$i+1]);					
				        }
				    }
			     }
				
			}
			//error_log(print_r($runMvc, true), 0);
			//error_log(print_r($request, true), 0);
			return array("mvc" => $runMvc, "request"=> $request);
		} 

		//No matching route - start from app name; transform directive to MVC and params
		$trimUri = ltrim($request->requestURI(), "/");
		$uelem= explode("/", $trimUri);
		if (count($uelem) < 4) {
			//wrong route or no matching at all
			return NULL;
		}

		$app = Framework::app($uelem[0]);

		//if $app is null, no matching routes
		if (!is_null($app)) {
			$mvcVal = new MvcValue();
			$mvcVal->setNames($app->relativePath(), $app->name(), $uelem[1], Route::fromHyphenToCamelCase($uelem[2], TRUE), Route::fromHyphenToCamelCase($uelem[3]));
			for ($i=4; $i<count($uelem); $i=$i+2) {
			     $uelem[$i+1] = isset($uelem[$i+1]) ? $uelem[$i+1] : NULL;
			     $request->setParam($uelem[$i],  $uelem[$i+1]);
			     $request->setGETParam($uelem[$i],  $uelem[$i+1]);
		    }
		    return array("mvc" => $mvcVal, "request"=> $request);
		}
		
		return NULL;
	}
	
	/**
	 * Change hyphen name into camel case
	 * @param string $name
	 * @param boolean $hasFirstUppercase Decide if the first character is uppercase.
	 * @return string A CamelCase text.
	 */
	public static function fromHyphenToCamelCase($name, $hasFirstUppercase = FALSE) {
		$words = explode('-', strtolower($name));
		if (count($words) == 1) {
			$hyphenName = Route::fromCamelCaseToUcHyphen($name);
			$hyphenNameParts = explode("-", $hyphenName);
			if ($hasFirstUppercase) {
				$hyphenNameParts[0] = ucfirst($hyphenNameParts[0]);
				$oneName = join("", $hyphenNameParts);
			} else {
				if(false === function_exists('lcfirst')) {
					$hyphenNameParts[0] = Route::lcFirst($hyphenNameParts[0]);
				} else {
					$hyphenNameParts[0] = lcfirst($hyphenNameParts[0]);
				}
				$oneName = join("", $hyphenNameParts);
			}
			return $oneName;
		}
	
		$camelCaseName = '';
		$index = 0;
		foreach ($words as $word) {
			if (!$hasFirstUppercase && $index ==0) {
				$camelCaseName .= trim($word);
			} else {
				$camelCaseName .= ucfirst(trim($word));
			}
			$index++;
		}
		return $camelCaseName;
	}
	
// 	/**
// 	 * Change to camel case
// 	 * @param string $name
// 	 * @return string A CamelCase text.
// 	 */
// 	public static function  convertToCamelCase($name) {
// 		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $name);
// 		$name = strtolower($name);
// 		$words = explode('_', strtolower($name));
// 		$camelCaseName = '';
		 
// 		foreach ($words as $word) {
// 			$camelCaseName .= ucfirst(trim($word));
// 		}
	
// 		return $camelCaseName;
// 	}
	
	/**
	 * Change to hyphen name
	 * @param string $name
	 * @return string A hyphen text.
	 */
	public static function fromCamelCaseToHyphen($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','-$1', $name);
		$name = strtolower($name);
		return $name;
	}
	
	/**
	 * Change to CamelCase
	 * @param string $name
	 * @return string A camel case.
	 */
	public static function fromCamelCaseToUcHyphen($name) {
		$name = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $name);
		$name = strtolower($name);
		$words = explode('_', strtolower($name));
		foreach ($words as $key => $word) {
			$words[$key] = ucfirst(trim($word));
		}
		$name = join("-", $words);
		return $name;
	}
	
	/**
	 * Make first character lower case.
	 * @param string $str
	 * @return string A text with first lower case character.
	 */
	public static function lcFirst($str) {
		$str[0] = strtolower($str[0]);
		return (string)$str;
	}
	
}
?>
