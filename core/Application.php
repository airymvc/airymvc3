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
 * This class defines all the variable that each application needs
 *
 * @package airymvc\core\Application
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 *
 */
class Application{
	
	 protected $name;
	 
	 protected $relativePath;
	 
	 protected $documentRoot;

	 protected $serverName;
	 
	 protected $config;
	 
	 protected $appConfigFile;
     
     public function __construct($name, $serverName, $documentRoot, $path) {
     	$this->setName($name);
     	$this->setServerName($serverName);
     	$this->setDocumentRoot($documentRoot);
     	$this->setRelativePath($path);
     	
     	//setup application map file
     	$appConfigMapFile = null;
     	$appEnv = "dev";
     	$appConfigMapFile1 = $this->documentRoot . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.map.json";
     	$appConfigMapFile2 = $this->documentRoot . DIRECTORY_SEPARATOR . "config.map.json";
     	
     	if (file_exists($appConfigMapFile1)) {
     	    $appConfigMapFile = $appConfigMapFile1;
     	} else if (file_exists($appConfigMapFile2)) {
     		$appConfigMapFile = $appConfigMapFile2;
     	} else if (file_exists(Framework::configMapFile())) {
     		$appConfigMapFile = Framework::configMapFile();
     		error_log("No application specific config.map.json file for {$name}. Use framework's config.map.json instead.");
     	} else {
     		error_log("Missing the config.map.json file for application and framework. Environment will be assumed to use dev so it will be config.dev.json file.");
     	}
     	
     	//determine environment based on $appConfigMapFile
     	$envArray = json_decode(file_get_contents($appConfigMapFile ), true);
     	foreach ($envArray['%env_map'] as $envElem) {
     		if ($envElem["%server_name"] == Framework::getServerName()) {
     			$env = $envElem["%env"];
     		}
     	}
     	
     	if ($env == "" || $env == null) {
     	    $env = $appEnv;
     	}
     	
     	//set application config file 
     	$appConfigFile1 = $this->documentRoot . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.{$env}.json";
     	$appConfigFile2 = $this->documentRoot . DIRECTORY_SEPARATOR . "config.{$env}.json";
     	
     	error_log($appConfigFile1);
     	error_log($appConfigFile2);

     	if (file_exists($appConfigFile1)) {
     		$this->appConfigFile($appConfigFile1);
     	} else if (file_exists($appConfigFile2)) {
     		$this->appConfigFile($appConfigFile2);
     	} else {
     		error_log("No application specific log file for {$name}. May use framework's instead.");
     	}
     }
     
     public function setName($name) {
     	$this->name = $name;
     }
	
     public function name() {
     	return $this->name;
     }
     
     public function setRelativePath($path) {
     	$this->relativePath = $path;
     }
     
     public function relativePath() {
     	return $this->relativePath;
     }
	public function documentRoot() {
		return $this->documentRoot;
	}
	public function setDocumentRoot($documentRoot) {
		$this->documentRoot = $documentRoot;
		return $this;
	}
	public function serverName() {
		return $this->serverName;
	}
	public function setServerName($serverName) {
		$this->serverName = $serverName;
		return $this;
	}
	public function config() {
		return $this->config;
	}
	public function setConfig($configArray) {
		$config = new Config($configArray);
		$this->config = $config;
		return $this;
	}
	
	public function appConfigFile($file = NULL) {
		//get method
		if (is_null($file)) {
			return $this->appConfigFile;
		}
		//set method
		$this->appConfigFile = $file;
	}
	
}
?>
