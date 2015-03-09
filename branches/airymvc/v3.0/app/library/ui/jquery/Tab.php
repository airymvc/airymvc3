<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * A Tab UI element.
 *
 * @filesource
 * @package framework\app\library\ui\jquery\Tab
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Tab extends JUIComponent{
    
	/**
	 * 
	 * @var array $_tabs
	 */
    protected $_tabs;
    
    /**
     * 
     * @param string $id
     * @param string $class
     */
    public function __construct($id, $class = null) {
        $this->_id = $id;
        $this->setAttribute('id', $id);
        if (!is_null($class)) {
            $this->setAttribute('class', $class);
        }
    }

    
    /**
     * $tabLink is the identifer for the tab. It could be a URL or #Identifer
     * 
     * @param string $label
     * @param string $tabLink
     * @param boolean $isAjax Default value = false
     */
    public function addTab($label, $tabLink, $isAjax = false){
        $this->_tabs[$tabLink] = array($label, $isAjax);
    }  
    

    /**
     *
     * @see JUIComponent::render()
     */
    public function render()
    {

        $tabText = $this->appendTabHtml(); 
        /**
         * Add Javascript support, follow the Jquery Tool Tabs
         */
        $tabText = $tabText . $this->appendTabJs($this->_id);        
        $this->_elementText = $tabText;
   		$this->attachJs();
        return $this->_elementText;
    }
    
    
    /**
     * 
     * @return string
     */
    protected function appendTabHtml() 
    {
        $tabText = "<div ";
        foreach ($this->_attributes as $key => $value)
        {
            $tabText = $tabText . " " . $key ."=\"".$value ."\"";
        }
        $tabText = $tabText . ">";
        $tabText = $tabText . "<ul>";
        
        foreach ($this->_tabs as $tabLink => $vars) {
            if (!$vars[1]) {
                $tabText = $tabText . "<li><a href='#" . $tabLink . "'>" . $vars[0] . "</a></li>";
            } else {
                $tabText = $tabText . "<li><a href='". $tabLink . "'>" . $vars[0] . "</a></li>";                
            }
        }
        
        $tabText = $tabText. '</ul>';
        
        foreach ($this->_tabs as $tabLink => $class) {
            $vars = $this->_tabs[$tabLink];
            //#vars[1] defines isAjax or not
            if (!$vars[1]) {
                $tabText = $tabText . "<div id='" . $tabLink . "'>";
                /**
                * Render the form elements here 
                */
                if (!is_null($this->_elements[$tabLink]) && isset($this->_elements[$tabLink])) {
                    foreach ($this->_elements[$tabLink] as $elemKey => $element)
                    {
                        $tabText = $tabText . $element->render();
                    }
                }
                $tabText = $tabText . "</div>";
            }
        }
        
        $tabText = $tabText . "</div>";
        
        return $tabText;
    }
    
    
    /**
     * 
     * @param string $tabId
     * @return string
     */
    protected function appendTabJs($tabId) {
        
        $tabText = '<script type="text/javascript">'
        		 . '$(function() {'
        		 . '$("div#' . $tabId .'").tabs();'
        	     . '});'
        	     . '</script>';
        
        return $tabText;
    }
}

?>
