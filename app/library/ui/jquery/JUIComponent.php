<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The fieldset html UI component
 *
 * @filesource
 * @package framework\app\library\ui\jquery\JUIComponent
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class JUIComponent implements JsUIComponentInterface {
    
    protected $_id;
    protected $_attributes = array();
    protected $_elements = array();
    protected $_elementText;
    protected $_javascriptText;
    protected $_javascript = array();
    
    /**
     * attributes is a key-value structure that stores all the form attribtes 
     */
    public function setAttribute($key, $value)
    {
        $this->_attributes[$key] =  $value;
    }
    
    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }
    //Use $tabLink as an unique identifer for tab container
    public function setElement($tabLink, $element)
    {
        $this->_elements[$tabLink][] =  $element;
    }
    
    public function setElements($tabLink, $elements)
    {
        $this->_elements[$tabLink] = $elements;
    }
        
    public function appendJavascript($javascript) {
    	$this->_javascript[] = $javascript;
    }
    
    public function renderJs() {
    	$this->_javascriptText = "";
        foreach ($this->_javascript as $jsText) {
    		$this->_javascriptText .= $jsText;
    	}
    	return $this->_javascriptText;
    }
    
    protected function attachJs() {
        $this->renderJs();
        $this->_elementText .= $this->_javascriptText;
    }
    
    public function render(){
		$this->attachJs();
        return $this->_elementText;
    }
     
}

?>
