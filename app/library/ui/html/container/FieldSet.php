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
 * @package framework\app\library\ui\html\container\FieldSet
 * @license New BSD license - at this URL: http://opensource.org/licenses/BSD-3-Clause
 */
class FieldSet extends AbstractContainer{

    protected $_fsText;
    protected $_legend;
    
    /**
     * @param string $label
     */
    public function setLabel($label){
        $this->_legend = $label;
    }
    
    /**
     * @see UIComponent::render()
     */
    public function render()
    {
        $fsText = "<fieldset";
        foreach ($this->_attributes as $key => $value)
        {
            $fsText = $fsText . " " . $key ."=\"".$value ."\"";
        }
        $fsText = $fsText . ">";
        $fsText = $fsText . "<legend>" . $this->_legend . "</legend>";      
        /**
         * Render the form elements here 
         */
        foreach ($this->_elements as $key => $element)
        {
            $fsText = $fsText . $element->render();
        }
        
        $fsText = $fsText . "</fieldset>";
        $this->_fsText = $fsText;
        
        return $this->_fsText;
    }
}

?>
