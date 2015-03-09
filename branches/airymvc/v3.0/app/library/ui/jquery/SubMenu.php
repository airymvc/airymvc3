<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * SubMenu is attached under a specific MenuItem.
 *
 * @filesource
 * @package framework\app\library\ui\jquery\MenuItem
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class SubMenu {
    
	/**
	 * 
	 * @var array $_items
	 */
	protected $_items;
    
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
     * @return MenuItem|NULL
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
     * 
     * @return string
     */
    public function render()
    {
    	$menuHtml = "<ul>";
    	foreach ($this->_items as $itemId => $menuItem) {
    		if ($menuItem instanceof MenuItem) {
    			$menuItemHtml = $menuItem->render();
    			$menuHtml .= sprintf("<li>%s</li>", $menuItemHtml);    			
    		} else {
    			$menuHtml .= "<li></li>";     			
    		}
    	}
    	$menuHtml .= "</ul>";
    	return $menuHtml;
    }
	
}