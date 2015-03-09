<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * The select item in the menu.
 *
 * @filesource
 * @package framework\app\library\ui\jquery\MenuItem
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class MenuItem {
	
	/**
	 * 
	 * @var string $label
	 */
	private $label;
	
	/**
	 * 
	 * @var string $link
	 */
	private $link;
	
	/**
	 * 
	 * @var string $iconCssClass
	 */
	private $iconCssClass;
	
	/**
	 * 
	 * @var string $subMenu
	 */
	private $subMenu;
	
	/**
	 * 
	 * @param string $label
	 * @param string $link
	 * @param string $iconCssClass
	 * @param string $subMenu
	 */
	public function __construct($label, $link, $iconCssClass = NULL, $subMenu = NULL) {
		$this->label = $label;
		$this->link  = $link;
		$this->iconCssClass = $iconCssClass;
		$this->subMenu = $subMenu;
	}
	
	
	/**
	 * 
	 * @return string
	 */
	public function render() {
		$html = "";
		if (is_null($this->iconCssClass)) {
			$html = sprintf('<a href="%s">%s</a>', $this->link, $this->label);
		} else {
			$html = sprintf('<a href="%s"><span class="%s"></span>%s</a>', $this->link, $this->iconCssClass, $this->label);
		}
		if (!is_null($this->subMenu) && $this->subMenu instanceof SubMenu) {
			$html .= $this->subMenu->render();
		}
		return $html;
	}
}