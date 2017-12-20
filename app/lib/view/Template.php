<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\app\lib\view;

require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Mvc.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'MvcValue.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Route.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib'. DIRECTORY_SEPARATOR . 'HttpClient.php';
require_once __AIRYMVC_ROOT__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lib'. DIRECTORY_SEPARATOR . 'SubMvcDispatch.php';

use airymvc\core\Mvc;
use airymvc\app\lib\HttpClient;
use airymvc\core\MvcValue;
use airymvc\core\Route;
use airymvc\app\lib\SubMvcDispatch;

/**
 * This handles the template and translation. The translation - the different language
 * word mappings are defined in the language files in the language folder (default folder is "lang").
 *
 * @package framework\app\library\lang\Language
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Template {
	
	protected $file;
	
	protected $mapFile;
	
	protected $viewKey;
	
	protected $urlMapParam;
	
	protected $map;
	
	public function __construct($file, $mapFile, $urlMapParam) {
		$this->setFile($file);
		$this->setMapFile($mapFile);
		$this->setUrlMapParam($urlMapParam);
		$this->map();
	}
	
	
	public function file() {
		return $this->file;
	}
	public function setFile($file) {
		$this->file = $file;
		return $this;
	}
	public function mapFile() {
		return $this->keyValueFile;
	}
	public function setMapFile($keyValueFile) {
		$this->keyValueFile = $keyValueFile;
		return $this;
	}

	
	public function urlMapParam() {
		return $this->urlMapParam;
	}
	public function setUrlMapParam($urlMapParam) {
		$this->urlMapParam = $urlMapParam;
		return $this;
	}
	
	public function render($request, $currentMapName = NULL) {
		$templateScript = "";
		$file = Mvc::templateDir() . DIRECTORY_SEPARATOR . $this->file();
		if (file_exists($file)) {
			$templateScript = file_get_contents($file);
			$tplMapKeys = $this->getTemplateMapKeys($templateScript);
			$restTplMapKeys = $tplMapKeys;
			$mapValues = $this->currentMapValues($currentMapName);
			$mapRoutes = $this->currentMapRoutes($currentMapName);

			//replace map key with map value
			foreach($tplMapKeys as $key => $tplMapKey) {
				if (!is_array($tplMapKey)) {
					$search = "%{" . $tplMapKey . "}%";
					$replace = isset($mapValues[$tplMapKey]) ? $mapValues[$tplMapKey] : NULL;
					if (!is_null($replace)) {
						$templateScript = str_replace($search, $replace, $templateScript);
						unset($restTplMapKeys[$key]);
					}
				}
			}
			foreach ($restTplMapKeys as $key => $restTplMapKey) {
				if (!is_array($restTplMapKey) && isset($mapRoutes[$restTplMapKey])) {
					$mapRoute = $mapRoutes[$restTplMapKey];
					$subMvc = new MvcValue();
					$subMvc->setNames(Mvc::$currentApp->relativePath(), Mvc::$currentApp->name(), $mapRoute[0],
							          Route::fromHyphenToCamelCase($mapRoute[1], TRUE), Route::fromHyphenToCamelCase($mapRoute[2]));
					$replace = SubMvcDispatch::run($request, $subMvc);
					$search = "%{" . $restTplMapKey . "}%";
					if (!empty($replace)) {
						$templateScript = str_replace($search, $replace, $templateScript);
					}
				}
			}	
		}

		return $templateScript;
	}
	
	public function viewKey() {
		$currentMap = $this->map();
		if (is_null($currentMap)) {
			return NULL;
		}
		$this->setViewKey($currentMap["%view_key"]);

		return $this->viewKey;
	}
	
	public function setViewKey($viewKey) {
		$this->viewKey = $viewKey;
		return $this;
	}

	public function map() {
		if (!is_null($this->map)) {
			return $this->map;
		}

		$file = Mvc::templateMapDir() . DIRECTORY_SEPARATOR . $this->mapFile();
		if (file_exists($file)) {
			$mapContent = file_get_contents($file);
			$this->map = json_decode($mapContent, TRUE);
		}

		return $this->map;		
	}
	
	
	public function currentMapValues($currentMapName) {
		$currentMap = $this->map();
		if (is_null($currentMap)) {
			return NULL;
        }
        if (is_null($currentMapName)) {
        	$currentMapName = $currentMap["%map_names"][0];
        }

		foreach ($currentMap["%maps"] as $map) {
			if ($map["%name"] == $currentMapName) {
				return $map["%value"];
			}
		}
		return NULL;
	}
	
	public function currentMapRoutes($currentMapName) {
		$currentMap = $this->map();
		if (is_null($currentMap)) {
			return NULL;
		}
		if (is_null($currentMapName)) {
			$currentMapName = $currentMap["%map_names"][0];
		}
		
		foreach ($currentMap["%maps"] as $map) {
			if ($map["%name"] == $currentMapName) {
				return $map["%route"];
			}
		}
		return NULL;
		
	}

	/**
	 * Replace the value by the key defined in the template key-value mapping file.
	 * @param string $content
	 * @return array keys in the template file content
	 */
	public function getTemplateMapKeys($content) {
		 $matches = array();
         preg_match_all('/(%({\w*})({\w*})%|%({\w*})%)/', $content, $matches);

         $keys = array();
         foreach ($matches[0] as $match) {
         	$removeHt = trim(str_replace("}%", "", str_replace("%{", "", $match)));
         	$keyElems = explode("}{", $removeHt);
         	if (count($keyElems) > 1) {
         		$keys[] = $keyElems;
         	} else {
         		$keys[] = $removeHt;
         	}
         }
         
         return $keys;
		 
	}

    
}

?>
