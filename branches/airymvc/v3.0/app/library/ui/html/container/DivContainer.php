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
 * @package framework\app\library\ui\html\container\DivContainer
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class DivContainer extends AbstractContainer{

	/**
	 * @var HTML div text
	 */
    protected $_divText;
    
    /**
     * @see UIComponent::render()
     */
    public function render()
    {
        $divText = "<div ";
        foreach ($this->_attributes as $key => $value)
        {
            $divText .=  " " . $key ."=\"".$value ."\"";
        }
        $divText .= ">";
        
        /**
         * Render the form elements here 
         */
        foreach ($this->_elements as $key => $element)
        {
            $divText .= $element->render();
        }
        
        $divText .= "</div>";
        $this->_divText = $divText;
        return $this->_divText;
    }
}

?>
