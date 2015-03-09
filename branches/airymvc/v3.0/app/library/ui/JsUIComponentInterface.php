<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The interface of the UI component that uses Javascript
 *
 * @package framework\app\library\ui\JsUIComponentInterface
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
interface JsUIComponentInterface {
	
	/**
	 * @param string $javascript
	 */
	public function appendJavascript($javascript);
	
	/**
	 * Render the component script in the view.
	 */
	public function render();
}