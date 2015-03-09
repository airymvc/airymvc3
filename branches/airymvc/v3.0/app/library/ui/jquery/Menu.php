<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The menu component.
 *
 * @filesource
 * @package framework\app\library\ui\jquery\Menu
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class Menu extends JUIComponent{
	
	/**
	 * 
	 * @var array $_items
	 */
    protected $_items;
    
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
 	 * @example Array(0 => an instance of MenuItem)
 	 * @param Array $menuItems
 	 */
    public function addMenuItem(Array $menuItems){
    	foreach ($menuItems as $itemId => $itemValue) {
        	$this->_items[$itemId] = $itemValue;
    	}
    }  
    
    
    /**
     * 
     * @param string $MenuItemId
     * @return string|NULL
     */
    public function getMenuItem($MenuItemId) {
    	foreach ($this->_items as $itemId => $itemValue) {
    		if ($itemId == $MenuItemId) {
    			return $itemValue;
    		}
    	}
    	return NULL;
    }
   
    /**
     * @see JUIComponent::render()
     */
    public function render()
    {

        $menuHtml = $this->appendMenuHtml(); 
        /**
         * Add Javascript support, follow the Jquery Tool Menu
         */
        $this->_elementText = $menuHtml . $this->appendJs($this->_id);
   		$this->attachJs();
        return $this->_elementText;
    }
    
    
	/**
 	 * 
 	 * @return string
 	 */
    protected function appendMenuHtml() 
    {
        $menuText = "<ul ";
        foreach ($this->_attributes as $key => $value)
        {
            $menuText = $menuText . " " . $key ."=\"".$value ."\"";
        }
        $menuText = $menuText . ">";
    	foreach ($this->_items as $itemId => $menuItem) {
    		if ($menuItem instanceof MenuItem) {
    			$menuItemHtml = $menuItem->render();
    			$menuText .= sprintf("<li>%s</li>", $menuItemHtml);    			
    		} else {
    			$menuText .= "<li></li>";     			
    		}
    	}
    	$menuText .= "</ul>";
        
        return $menuText;
    }
    
    /**
     * 
     * @param string $menuId
     * @return string
     */
    protected function appendJs($menuId) {
        
        $menuJs = '<script type="text/javascript">'
        		 . '$(function() {'
        		 . '$("#' . $menuId .'").menu();'
        	     . '});'
        	     . '</script>';
        
        return $menuJs;
    }
	
}

