<?php
/**
 * AiryMVC Framework
 *
 * @category AiryMVC
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 * @author: Hung-Fu Aaron Chang
 */
/**
 * This handles the html scripts
 *
 * @filesource
 * @package framework\app\library\ui\html\component\HtmlScript
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class HtmlScript extends UIComponent{
	
	/**
	 * HTML text
	 * @var string $_html
	 */
    protected $_html;
    
    /**
     * @var string $_id
     */
    private $_id;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * @param string $html
     * @param string $id Default value =  NULL
     */
    public function setScript($html, $id = NULL)
    {
        $this->_html =$html;
        $this->setId($id);
    }

    /**
     * @see UIComponent::render()
     */
    public function render()
    {
        return $this->_html;
    }
}

?>
