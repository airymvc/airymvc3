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
class Request{

	protected $type;
	
	protected $params;
	
	protected $POSTParams;
	
	protected $GETParams;
	
	protected $serverName;

	protected $httpHost;
	
	protected $requestTime;
	
	protected $userAgent;
	
	protected $referer;
	
	protected $scriptName;

	protected $remoteHost;
	
	protected $remoteAddress;

	protected $appName;
	
	protected $moduleName;
	
	protected $controllerName;
	
	protected $actionName;
	
	protected $templateMap;
	
	public function set() {
		$this->type = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : NULL;
		$this->httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : NULL;
		$this->requestTime = isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : NULL;
		$this->scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : NULL;
		$this->remoteHost = isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : NULL;
		$this->remoteAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL;
		$this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
		$this->requestUri =  isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL;
		$this->userAgent =  isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL;
		$this->serverName =  isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : NULL;
		
		$this->prepareParams();
	}
	
	private function prepareParams() {
		$keys = array();
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$keys = array_keys($_GET);
		}else {
			$qsPieces = explode('&', $_SERVER['QUERY_STRING']);
			foreach ($qsPieces as $piece) {
				$x = explode('=', $piece);
				$value = isset($x[1]) ? $x[1] : "";
				$this->params[$x[0]] = $value;
				$this->GETParams[$x[0]] = $value;
			}
			$keys = array_keys($_POST); //get form variables
		}
		
		foreach ($keys as $key => $value) {
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				//make to lower case
				$this->params[strtolower($value)] = $_GET[$value];
				$this->GETParams[strtolower($value)] = $_GET[$value];
			} else {
				//make to lower case
				$this->params[strtolower($value)] = $_POST[$value];
				$this->POSTParams[strtolower($value)] = $_POST[$value];
			}
		}
	}
	
	public function setParam($key, $value) {
		$this->params[$key] = $value;
	}
	
	public function setGETParam($key, $value) {
		$this->GETParams[$key] = $value;
	}
	
	public function setPOSTParam($key, $value) {
		$this->POSTParams[$key] = $value;
	}
	
	
	public function param($name) {
		if (!isset($this->params[$name])) {
			return NULL;	
		}
		return $this->params[$name];
	}

	public function params() {
		return $this->params;
	}
	
	public function requestURI() {
		return $this->requestUri;
	}
	
	public function type() {
		return $this->type;
	}
	public function setType($type) {
		$this->type = $type;
		return $this;
	}
	public function POSTParams() {
		return $this->POSTParams;
	}
	public function setPOSTParams($POSTParams) {
		$this->POSTParams = $POSTParams;
		return $this;
	}
	public function GETParams() {
		return $this->GETParams;
	}
	public function setGETParams($GETParams) {
		$this->GETParams = $GETParams;
		return $this;
	}
	public function serverName() {
		return $this->serverName;
	}
	public function setServerName($serverName) {
		$this->serverName = $serverName;
		return $this;
	}
	public function httpHost() {
		return $this->httpHost;
	}
	public function setHttpHost($httpHost) {
		$this->httpHost = $httpHost;
		return $this;
	}
	public function requestTime() {
		return $this->requestTime;
	}
	public function setRequestTime($requestTime) {
		$this->requestTime = $requestTime;
		return $this;
	}
	public function userAgent() {
		return $this->userAgent;
	}
	public function setUserAgent($userAgent) {
		$this->userAgent = $userAgent;
		return $this;
	}
	public function referer() {
		return $this->referer;
	}
	public function setReferer($referer) {
		$this->referer = $referer;
		return $this;
	}
	public function scriptName() {
		return $this->scriptName;
	}
	public function setScriptName($scriptName) {
		$this->scriptName = $scriptName;
		return $this;
	}
	public function remoteHost() {
		return $this->remoteHost;
	}
	public function setRemoteHost($remoteHost) {
		$this->remoteHost = $remoteHost;
		return $this;
	}
	public function remoteAddress() {
		return $this->remoteAddress;
	}
	public function setRemoteAddress($remoteAddress) {
		$this->remoteAddress = $remoteAddress;
		return $this;
	}
	public function appName() {
		return $this->appName;
	}
	public function setAppName($appName) {
		$this->appName = $appName;
		return $this;
	}
	public function moduleName() {
		return $this->moduleName;
	}
	public function setModuleName($moduleName) {
		$this->moduleName = $moduleName;
		return $this;
	}
	public function controllerName() {
		return $this->controllerName;
	}
	public function setControllerName($controllerName) {
		$this->controllerName = $controllerName;
		return $this;
	}
	public function actionName() {
		return $this->actionName;
	}
	public function setActionName($actionName) {
		$this->actionName = $actionName;
		return $this;
	}
	public function templateMap($mapUrlParam) {
		return $this->param($mapUrlParam);
	}
	
	public function setTemplateMap($templateMap) {
		$this->templateMap = $templateMap;
		return $this;
	}
	
}
?>
