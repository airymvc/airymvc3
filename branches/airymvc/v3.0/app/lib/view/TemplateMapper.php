<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
namespace airymvc\app\lib\view;

use airymvc\core\Mvc;
use airymvc\core\Route;
/**
 * This handles the language code and translation. The translation - the different language
 * word mappings are defined in the language files in the language folder (default folder is "lang").
 *
 * @package framework\app\library\lang\Language
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class TemplateMapper {

	public static $templates;

	public static function templates() {
		if (!is_null(TemplateMapper::$templates)) {
			return TemplateMapper::$templates;
		}
		
		$app = Mvc::$currentApp;
		$tpls = $app->config()->templates();
		foreach ($tpls as $tpl) {
			$views = $tpl["%views"];
			foreach ($views as $view) {
				$moduleName = $view[0];
				$controllerName = "ALL";
				$actionName = "ALL";
				if (isset($view[1])) {
					$controllerName = $view[1];
				}
				if (isset($view[2])) {
					$actionName = $view[2];
				}
				$template = new Template($tpl["%template"], $tpl["%key_value"], $tpl["%map_url_param"]);
				TemplateMapper::$templates[$moduleName][$controllerName][$actionName] = $template;
			}
		}
		
		return TemplateMapper::$templates;
		
	}
	
	
	public static function getTemplate($moduleName, $controllerName, $actionName) {
		if (is_null(TemplateMapper::$templates)) {
			TemplateMapper::templates();
		}
		if (is_null(TemplateMapper::$templates)) {
			return NULL;
		}
		foreach (TemplateMapper::$templates as $mapModuleName => $controllerAction) {
			if ($moduleName == $mapModuleName) {
				if (isset(TemplateMapper::$templates[$mapModuleName]["ALL"])) {
					return TemplateMapper::$templates[$mapModuleName]["ALL"]["ALL"];
				}
				foreach ($controllerAction as $mapControllerName => $action) {
					if ($moduleName == $mapModuleName) {
						if ($controllerName == $mapControllerName || $controllerName == Route::fromHyphenToCamelCase($mapControllerName, TRUE)) {
							if (isset(TemplateMapper::$templates[$mapModuleName][$mapControllerName]["ALL"])) {
								return TemplateMapper::$templates[$mapModuleName][$mapControllerName]["ALL"];
							}
						}
					}
				
					foreach ($action as $mapActionName => $tpl) {
						if ($moduleName == $mapModuleName) {
							if ($controllerName == $mapControllerName || $controllerName == Route::fromHyphenToCamelCase($mapControllerName, TRUE)) {
								if ($actionName == $mapActionName || $actionName == Route::fromHyphenToCamelCase($mapActionName, FALSE) ) {
									return $tpl;
								}
							}
						}
				
					}
				}				
			}
		}
		return NULL;
	}
	
}

?>
