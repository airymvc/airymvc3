<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This helper class is used for composing the data that Router needs.
 *
 * @filesource
 * @package framework\core\RouterUrlRewriter
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class RouterUrlRewriter {
	
	public function remapGetAndPost($actionKeyword) {
		
		//Deal with $_GET for parsing module/controller/action/querystring
		if (isset($_GET[$actionKeyword])) {
			$actionWithQueryPath = $_GET[$actionKeyword];
			$params = explode("/", $actionWithQueryPath);
			$GETParams = array();
			if (empty($params[count($params)-1])) {
				array_pop($params);
			}
			if (count($params) >= 2) {
				for ($i=1; $i<count($params); $i=$i+2) {
					 $params[$i+1] = isset($params[$i+1]) ? $params[$i+1] : NULL;
					 $GETParams[$params[$i]] = $params[$i+1];
				}
			}
			//deal with action
			$GETParams[$actionKeyword] = $params[0];
			foreach ($GETParams as $key=>$value) {
				$_GET[$key] = $value;
			}
		}
			
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$qstringPieces = explode('&', $_SERVER['QUERY_STRING']);
		    $newQueryString = "";
			foreach ($qstringPieces as $piece) {
				$kv = explode("=", $piece);
				if ($kv[0] == $actionKeyword) {
					$params = explode("/", $kv[1]);
					$newQueryString .= $actionKeyword . "=" . $params[0] . "&";;
					for ($i=1; $i<count($params); $i=$i+2) {
						$newQueryString .= $params[$i] . "=" . $params[$i+1] . "&";
					}
				} else {
					$newQueryString .= $piece . "&";					
				}
			}
			$_SERVER['QUERY_STRING'] = rtrim($newQueryString, "&");
		}
		
	}
	
}